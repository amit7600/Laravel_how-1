<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\CampaignReport;
use App\Contact;
use App\Http\Controllers\EmailController;
use App\Phone;
use Carbon\Carbon;
use DB;
use Sentinel;
use Twilio\Rest\Client;

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
                $this->send_audio($campaign);
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
            dd($th);
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
                            DB::beginTransaction();
                            CampaignReport::create([
                                'user_id' => Sentinel::check()->id,
                                'type' => $campaign->campaign_type,
                                'status' => $data->status,
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
                            $response = $this->client->messages->create(
                                $contact_number,
                                [
                                    'from' => env('TWILIO_FROM'),
                                    'body' => $message,
                                    "mediaUrl" => array($url . $campaign->campaign_file),
                                ]
                            );
                            $response = \Curl::to('https://api.twilio.com/' . $response->uri)
                                ->withOption('USERPWD', $this->sid . ':' . $this->token)
                                ->get();
                            $data = json_decode($response);
                            DB::beginTransaction();
                            CampaignReport::create([
                                'user_id' => Sentinel::check()->id,
                                'type' => $campaign->campaign_type,
                                'status' => $data->status,
                                'date_sent' => Carbon::now(),
                                'direction' => $data->direction,
                                'toNumber' => $data->to,
                                'toContact' => $contact->contact_first_name,
                                'fromNumber' => $data->from,
                                'fromContact' => 'HowCalm',
                                'fromContact' => 'HowCalm',
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
            return $th->getMessage();
        }

    }

}
