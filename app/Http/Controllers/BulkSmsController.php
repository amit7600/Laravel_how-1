<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\CampaignReport;
use App\Contact;
use App\Http\Controllers\EmailController;
use App\Phone;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Mail;
use Sentinel;
use Twilio\Rest\Client;
use Twilio\Twiml;

class BulkSmsController extends Controller
{
    public function __construct(EmailController $mailController)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $this->client = new Client($sid, $token);
        $this->mailController = $mailController;
        $this->sid = $sid;
        $this->token = $token;

    }
    public function send_campaign($id)
    {
        try {
            $campaign = Campaign::whereId($id)->first();

            if ($campaign && $campaign->campaign_type == 1) {
                $response = $this->mailController->send_email($campaign);
            } elseif ($campaign && $campaign->campaign_type == 2) {
                $response = $this->send_sms($campaign);
            } elseif ($campaign && $campaign->campaign_type == 3) {
                $response = $this->send_audio($campaign);
            } elseif ($campaign && $campaign->campaign_type == 4) {
                $response = $this->send_sms($campaign);
                $response = $this->send_audio($campaign);
            }
            if ($response === true) {
                return redirect()->to('/campaigns')->with('success', 'Campaign send successfully');
            } else {
                return redirect()->to('/campaigns')->with('error', $response);

            }

        } catch (\Throwable $th) {
            //throw $th;d
            return redirect()->to('/campaigns')->with('error', $th->getMessage());

        }
    }
    public function send_sms($campaign)
    {
        try {
            $message = $campaign->body;
            $url = env('APP_URL');
            if ($campaign) {
                $recipients = $campaign->recipient != '' ? explode(',', $campaign->recipient) : '';

                foreach ($recipients as $key => $value) {
                    $contact = Contact::whereId($value)->first();

                    if ($contact) {
                        $phone_number = Phone::where('phone_recordid', $contact->contact_cell_phones)->where('phone_type', 'cell phone')->first();

                        if ($phone_number) {
                            $contact_number = $phone_number->phone_number;
                            $response = $this->client->messages->create(
                                $contact_number,
                                [
                                    'from' => env('TWILIO_FROM'),
                                    'body' => $campaign->body,
                                ]
                            );
                            $response = \Curl::to('https://api.twilio.com/' . $response->uri)
                                ->withOption('USERPWD', $this->sid . ':' . $this->token)
                                ->get();

                            $data = json_decode($response);
                            \Log::info($response);
                            DB::beginTransaction();
                            CampaignReport::create([
                                'user_id' => Sentinel::check()->id,
                                'type' => $campaign->campaign_type,
                                'status' => $data->status,
                                'contact_id' => $contact->id,
                                'date_sent' => Carbon::now(),
                                'direction' => $data->direction,
                                'toNumber' => $data->to,
                                'toContact' => $contact->contact_first_name,
                                'fromNumber' => $data->from,
                                'fromContact' => 'HowCalm',
                                'contact_id' => $value,
                                'body' => $data->body,
                                'campaign_id' => $campaign->id,
                            ]);
                            DB::commit();
                        }
                    }
                }
                return true;

            } else {
                return 'Campaign not found!';
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            return $th->getMessage();
        }
    }
    public function send_audio($campaign)
    {
        try {
            $message = $campaign->body;
            $url = env('APP_URL');
            if ($campaign) {
                $recipients = $campaign->recipient != '' ? explode(',', $campaign->recipient) : '';

                foreach ($recipients as $key => $value) {
                    $contact = Contact::whereId($value)->first();
                    if ($contact) {
                        $phone_number = Phone::where('phone_recordid', $contact->contact_office_phones)->where('phone_type', 'office phone')->first();

                        if ($phone_number) {
                            $contact_number = str_replace('-', '', $phone_number->phone_number);
                            $contact_number = strlen($contact_number) == 10 ? '+1' . $contact_number : $contact_number;

                            // DB::beginTransaction();
                            // CampaignReport::create([
                            //     'user_id' => Sentinel::check()->id,
                            //     'type' => $campaign->campaign_type,
                            //     'contact_id' => $contact->id,
                            //     'status' => 'sent',
                            //     'date_sent' => Carbon::now(),
                            //     'direction' => 'outbound',
                            //     'toNumber' => $contact_number,
                            //     'toContact' => $contact->contact_first_name,
                            //     'fromNumber' => env('TWILIO_FROM'),
                            //     'fromContact' => 'HowCalm',
                            //     // 'body' => $data->body,
                            //     'campaign_id' => $campaign->id,
                            //     'mediaurl' => $campaign->campaign_file,
                            // ]);
                            // DB::commit();

                            $response = $this->client->calls->create(
                                $contact_number, env('TWILIO_FROM'), array("url" => route("voice"), 'method' => 'GET')
                                // 'body' => $message,
                                // "mediaUrl" => array($url . $campaign->campaign_file),

                            );
                            // sleep(15);
                            $response = \Curl::to('https://api.twilio.com/' . $response->uri)
                                ->withOption('USERPWD', $this->sid . ':' . $this->token)
                                ->get();
                            $data = json_decode($response);
                            DB::beginTransaction();
                            CampaignReport::create([
                                'user_id' => Sentinel::check()->id,
                                'type' => $campaign->campaign_type,
                                'contact_id' => $contact->id,
                                'status' => $data->status,
                                'date_sent' => Carbon::now(),
                                'direction' => $data->direction,
                                'toNumber' => $data->to,
                                'toContact' => $contact->contact_first_name,
                                'fromNumber' => $data->from,
                                'fromContact' => 'HowCalm',
                                // 'body' => $data->body,
                                'campaign_id' => $campaign->id,
                                'mediaurl' => $campaign->campaign_file,
                            ]);
                            DB::commit();

                        } else {
                            return 'Phone number not found!';
                        }
                    } else {
                        return 'Contact not found!';
                    }
                }
                return true;

            } else {
                return 'Campaign not found!';
            }

        } catch (\Throwable $th) {
            return $th->getMessage();
        }

    }
    public function voice(Request $request)
    {
        // \Log::info($request->input('To'));
        $to = $request->input('To');
        // $phones = Phone::get();
        // $to = substr($to, 2);
        // $phone_recordid = '';
        // foreach ($phones as $key => $phone) {
        //     $phone_number = str_replace('-', '', $phone->phone_number);
        //     $phone_number = preg_replace('/[^A-Za-z0-9\-]/', '', $phone_number);
        //     if ($phone_number == $to || strpos($phone_number, $to)) {
        //         $phone_recordid = $phone->phone_recordid;
        //     }
        // }
        // if ($phone_recordid != '') {
        //     $contact = Contact::where();
        // }
        $to = $request->input('To');
        $campaingReport = CampaignReport::where('toNumber', $to)->orderBy('id', 'desc')->first();
        $url = '/uploads/campaigns/audio/Iphone_Ringtones_Download_1577856635.mp3';
        if ($campaingReport) {
            $campaign = Campaign::whereId($campaingReport->campaign_id)->first();
            $url = $campaingReport->mediaurl;
        }
        $response = new Twiml();
        $gather = $response->gather(
            ['numDigits' => '1', 'action' => '/callResponse',
                'method' => 'GET']
        );
        // $gather->say(
        //     'Hello , Devin Balkind',
        //     ['voice' => 'alice', 'language' => 'en-US']
        // );

        $gather->play(url($url));

        $gather->say(
            '',
            '',
            '',
            '',
            '',
            '',
            ['voice' => 'alice', 'language' => 'en-US']
        );

        return $response;

    }
    public function callResponse(Request $request)
    {
        try {
            $to = $request->input('To');

            $campaingReport = CampaignReport::where('toNumber', $to)->orderBy('id', 'desc')->first();
            if ($campaingReport) {
                CampaignReport::create([
                    'body' => $request->input('Digits'),
                    'user_id' => $campaingReport->type,
                    'type' => $campaingReport->user_id,
                    'contact_id' => $campaingReport->contact_id,
                    'status' => 'incoming',
                    'date_sent' => Carbon::now(),
                    'direction' => 'inbound-api',
                    'toNumber' => $campaingReport->fromNumber,
                    'toContact' => 'HowCalm',
                    'fromNumber' => $to,
                    'fromContact' => $campaingReport->toContact,
                    'campaign_id' => $campaingReport->campaign_id,
                    'mediaurl' => $campaingReport->mediaurl,
                ]);
            }
            $response = new Twiml();
            $response->say(
                'Thanks for your response. Enjoy!',
                ['voice' => 'alice', 'language' => 'en-US']
            );

            return $response;

        } catch (\Throwable $th) {
            \Log::info($th->getMessage());
        }
    }
    public function send_message(Request $request, $id)
    {
        $this->validate($request, [
            'message_type' => 'required',
        ]);
        try {
            $type = $request->get('message_type');
            $body = $request->get('message_body');
            $subject = $request->get('subject');
            $contact = Contact::whereId($id)->first();
            $contact_email = $contact->contact_email;
            $phone = $contact->cellphone->phone_number;
            if ($type == 'sms') {
                $response = $this->client->messages->create(
                    $phone,
                    [
                        'from' => env('TWILIO_FROM'),
                        'body' => $body,
                    ]
                );
                $response = \Curl::to('https://api.twilio.com/' . $response->uri)
                    ->withOption('USERPWD', $this->sid . ':' . $this->token)
                    ->get();

                $data = json_decode($response);

                DB::beginTransaction();
                CampaignReport::create([
                    'user_id' => Sentinel::check()->id,
                    'type' => '2',
                    'status' => $data->status,
                    'contact_id' => $contact->id,
                    'date_sent' => Carbon::now(),
                    'direction' => $data->direction,
                    'toNumber' => $data->to,
                    'toContact' => $contact->contact_first_name,
                    'fromNumber' => $data->from,
                    'fromContact' => 'HowCalm',
                    'contact_id' => $id,
                    'body' => $body,
                ]);
                DB::commit();
                return redirect()->back()->with('success', 'SMS sent succesfully!');

            } elseif ($type == 'email') {
                $from = env('MAIL_FROM_ADDRESS');
                $name = env('MAIL_FROM_NAME');
                $email = new \SendGrid\Mail\Mail();
                $email->setFrom($from, $name);
                $email->setSubject($subject);
                $email->addTo($contact_email, $contact->contact_first_name);
                $email->addContent("text/plain", $body);
                $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

                $response = $sendgrid->send($email);

                $error = '';
                if ($response->statusCode() == 401) {
                    $error = json_decode($response->body());
                }
                DB::beginTransaction();
                CampaignReport::create([
                    'user_id' => Sentinel::check()->id,
                    'type' => '1',
                    'status' => $response->statusCode() == 202 ? 'Delivered' : 'Undelivered',
                    'date_sent' => Carbon::now(),
                    'error_message' => $error != '' ? $error->errors[0]->message : '',
                    'toContact' => $contact->contact_first_name,
                    'fromNumber' => env('MAIL_FROM_ADDRESS'),
                    'toNumber' => $contact_email,
                    'direction' => 'outbound-api',
                    'fromContact' => 'HowCalm',
                    'contact_id' => $id,
                    'subject' => $subject,
                    'body' => $body,
                ]);
                DB::commit();
                return redirect()->back()->with('success', 'Email sent succesfully!');

            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function group_message(Request $request, $id)
    {
        $this->validate($request, [
            'message_type' => 'required',
        ]);

        try {
            $type = $request->get('message_type');
            $body = $request->get('message_body');
            $subject = $request->get('subject');
            $contacts = Contact::where('contact_group', 'LIKE', '%' . $id . '%')->get();
            foreach ($contacts as $key => $contact) {
                $phone = $contact->cellphone->phone_number;
                $contact_email = $contact->contact_email;

                if ($type == 'sms') {
                    $response = $this->client->messages->create(
                        $phone,
                        [
                            'from' => env('TWILIO_FROM'),
                            'body' => $body,
                        ]
                    );
                    $response = \Curl::to('https://api.twilio.com/' . $response->uri)
                        ->withOption('USERPWD', $this->sid . ':' . $this->token)
                        ->get();

                    $data = json_decode($response);

                    DB::beginTransaction();
                    CampaignReport::create([
                        'user_id' => Sentinel::check()->id,
                        'type' => '2',
                        'status' => $data->status,
                        'contact_id' => $contact->id,
                        'date_sent' => Carbon::now(),
                        'direction' => $data->direction,
                        'toNumber' => $data->to,
                        'toContact' => $contact->contact_first_name,
                        'fromNumber' => $data->from,
                        'fromContact' => 'HowCalm',
                        'body' => $body,
                    ]);
                    DB::commit();

                } elseif ($type == 'email') {
                    $from = env('MAIL_FROM_ADDRESS');
                    $name = env('MAIL_FROM_NAME');
                    $email = new \SendGrid\Mail\Mail();
                    $email->setFrom($from, $name);
                    $email->setSubject($subject);
                    $email->addTo($contact_email, $contact->contact_first_name);
                    $email->addContent("text/plain", $body);
                    $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

                    $response = $sendgrid->send($email);

                    $error = '';
                    if ($response->statusCode() == 401) {
                        $error = json_decode($response->body());
                    }
                    DB::beginTransaction();
                    CampaignReport::create([
                        'user_id' => Sentinel::check()->id,
                        'type' => '1',
                        'status' => $response->statusCode() == 202 ? 'Delivered' : 'Undelivered',
                        'date_sent' => Carbon::now(),
                        'error_message' => $error != '' ? $error->errors[0]->message : '',
                        'toContact' => $contact->contact_first_name,
                        'fromNumber' => env('MAIL_FROM_ADDRESS'),
                        'toNumber' => $contact_email,
                        'direction' => 'outbound-api',
                        'fromContact' => 'HowCalm',
                        'contact_id' => $contact->id,
                        'subject' => $subject,
                        'body' => $body,
                    ]);
                    DB::commit();

                }

            }
            if ($type == 'sms') {
                return redirect()->back()->with('success', 'SMS sent succesfully!');

            } else {
                return redirect()->back()->with('success', 'Email sent succesfully!');

            }

        } catch (\Throwable $th) {
            dd($th);
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function sendMultipleMessage(Request $request)
    {
        if ($request->get('sending_method') == 'contact') {
            $this->validate($request, [
                'contacts' => 'required',
            ]);

        } elseif ($request->get('sending_method') == 'group') {
            $this->validate($request, [
                'groups' => 'required',
            ]);
        }
        try {
            $type = $request->get('sending_type');
            $contacts = $request->get('contacts');
            $groups = $request->get('groups');
            $sending_method = $request->get('sending_method');
            $subject = $request->get('subject');
            $body = $request->get('body');

            if ($type == 'sms') {
                if ($sending_method == 'contact') {
                    foreach ($contacts as $key => $value) {
                        $phone = Phone::where('phone_recordid', $value)->first();
                        $contactData = Contact::where('contact_cell_phones', $value)->first();
                        $contact = $phone->phone_number;
                        if ($phone && $contact != '') {
                            $response = $this->client->messages->create(
                                $contact,
                                [
                                    'from' => env('TWILIO_FROM'),
                                    'body' => $body,
                                ]
                            );
                            $response = \Curl::to('https://api.twilio.com/' . $response->uri)
                                ->withOption('USERPWD', $this->sid . ':' . $this->token)
                                ->get();

                            $data = json_decode($response);

                            DB::beginTransaction();
                            CampaignReport::create([
                                'user_id' => Sentinel::check()->id,
                                'type' => '2',
                                'status' => $data->status,
                                'contact_id' => $contactData->id,
                                'date_sent' => Carbon::now(),
                                'direction' => $data->direction,
                                'toNumber' => $data->to,
                                'toContact' => $contactData->contact_first_name,
                                'fromNumber' => $data->from,
                                'fromContact' => 'HowCalm',
                                'body' => $body,
                            ]);
                            DB::commit();

                        }
                    }
                } else {
                    foreach ($groups as $key => $group) {
                        $findContacts = Contact::where('contact_group', 'LIKE', '%' . $group . '%')->get();
                        if (count($findContacts) > 0) {
                            foreach ($findContacts as $key => $contact) {
                                $response = $this->client->messages->create(
                                    $contact->cellphone->phone_number,
                                    [
                                        'from' => env('TWILIO_FROM'),
                                        'body' => $body,
                                    ]
                                );
                                $response = \Curl::to('https://api.twilio.com/' . $response->uri)
                                    ->withOption('USERPWD', $this->sid . ':' . $this->token)
                                    ->get();

                                $data = json_decode($response);
                                DB::beginTransaction();
                                CampaignReport::create([
                                    'user_id' => Sentinel::check()->id,
                                    'type' => '2',
                                    'status' => $data->status,
                                    'contact_id' => $contact->id,
                                    'date_sent' => Carbon::now(),
                                    'direction' => $data->direction,
                                    'toNumber' => $data->to,
                                    'toContact' => $contact->contact_first_name,
                                    'fromNumber' => $data->from,
                                    'fromContact' => 'HowCalm',
                                    'body' => $body,
                                ]);
                                DB::commit();

                            }
                        }
                    }

                }
                return redirect()->back()->with('success', 'SMS sent successfully!');
            } else {
                if ($sending_method == 'contact') {
                    foreach ($contacts as $key => $value) {

                        $contact = Contact::where('contact_cell_phones', $value)->first();
                        $contact_email = $contact->contact_email;
                        if ($contact && $contact_email != null) {
                            $from = env('MAIL_FROM_ADDRESS');
                            $name = env('MAIL_FROM_NAME');
                            $email = new \SendGrid\Mail\Mail();
                            $email->setFrom($from, $name);
                            $email->setSubject($subject);
                            $email->addTo($contact_email, $contact->contact_first_name);
                            $email->addContent("text/plain", $body);
                            $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

                            $response = $sendgrid->send($email);

                            $error = '';
                            if ($response->statusCode() == 401) {
                                $error = json_decode($response->body());
                            }
                            DB::beginTransaction();
                            CampaignReport::create([
                                'user_id' => Sentinel::check()->id,
                                'type' => '1',
                                'status' => $response->statusCode() == 202 ? 'Delivered' : 'Undelivered',
                                'date_sent' => Carbon::now(),
                                'error_message' => $error != '' ? $error->errors[0]->message : '',
                                'toContact' => $contact->contact_first_name,
                                'fromNumber' => env('MAIL_FROM_ADDRESS'),
                                'toNumber' => $contact_email,
                                'direction' => 'outbound-api',
                                'fromContact' => 'HowCalm',
                                'contact_id' => $contact->id,
                                'subject' => $subject,
                                'body' => $body,
                            ]);
                            DB::commit();

                        }
                    }
                } else {
                    foreach ($groups as $key => $group) {
                        $findContacts = Contact::where('contact_group', 'LIKE', '%' . $group . '%')->get();
                        if (count($findContacts) > 0) {
                            foreach ($findContacts as $key => $contact) {
                                $contact_email = $contact->contact_email;

                                if ($contact && $contact_email != null) {
                                    $from = env('MAIL_FROM_ADDRESS');
                                    $name = env('MAIL_FROM_NAME');
                                    $email = new \SendGrid\Mail\Mail();
                                    $email->setFrom($from, $name);
                                    $email->setSubject($subject);
                                    $email->addTo($contact_email, $contact->contact_first_name);
                                    $email->addContent("text/plain", $body);
                                    $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

                                    $response = $sendgrid->send($email);

                                    $error = '';
                                    if ($response->statusCode() == 401) {
                                        $error = json_decode($response->body());
                                    }
                                    DB::beginTransaction();
                                    CampaignReport::create([
                                        'user_id' => Sentinel::check()->id,
                                        'type' => '1',
                                        'status' => $response->statusCode() == 202 ? 'Delivered' : 'Undelivered',
                                        'date_sent' => Carbon::now(),
                                        'error_message' => $error != '' ? $error->errors[0]->message : '',
                                        'toContact' => $contact->contact_first_name,
                                        'fromNumber' => env('MAIL_FROM_ADDRESS'),
                                        'toNumber' => $contact_email,
                                        'direction' => 'outbound-api',
                                        'fromContact' => 'HowCalm',
                                        'contact_id' => $contact->id,
                                        'subject' => $subject,
                                        'body' => $body,
                                    ]);
                                    DB::commit();

                                }
                            }
                        }
                    }

                }
                return redirect()->back()->with('success', 'Email sent successfully!');

            }

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());

        }
    }

}
