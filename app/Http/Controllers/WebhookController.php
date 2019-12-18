<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\CampaignReport;
use App\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpMimeMailParser\Parser;
use Twilio\Rest\Client;

class WebhookController extends Controller
{
    public function __construct()
    {
        $this->accountSid = env('TWILIO_SID');
        $authToken = env('TWILIO_TOKEN');
        $this->twilioNumber = env('TWILIO_FROM');

        $this->twilio = new Client($this->accountSid, $authToken);
    }
    public function index(Request $request)
    {
        try {
            $sms_body = $request->input('Body');
            $from = $request->input('From');
            $full = "From: " . $from . " SMS: " . $sms_body;
            $fromContact = '';
            $toContact = 'HowCalm';
            $contact_id = '';
            $campaignDetail = CampaignReport::where('toNumber', $from)->orderBy('id', 'desc')->first();
            if ($campaignDetail) {
                $campaignId = $campaignDetail->campaign_id;
                $campaign = Campaign::whereId($campaignDetail->campaign_id)->first();

                $contactId = $campaign ? explode(',', $campaign->recipient) : [];

                foreach ($contactId as $key => $value) {
                    $contact = Contact::whereId($value)->where('contact_email', $from)->first();
                    if ($contact) {
                        $fromContact = $contact->contact_first_name;
                        $contact_id = $contact->id;
                    }
                }
            }

            $webhook = new CampaignReport();
            $webhook->body = $sms_body;
            $webhook->status = 'Incoming';
            $webhook->fromContact = $fromContact;
            $webhook->toContact = $toContact;
            $webhook->contact_id = $contact_id;

            if ($campaignDetail != null) {
                $webhook->campaign_id = $campaignDetail->campaign_id;
            }
            $webhook->direction = 'Inbound-api';
            $webhook->toNumber = $request->input('To');
            $webhook->fromNumber = $request->input('From');
            $type = 2;
            if ($request->input('MediaUrl0') != null) {
                $type = 4;
                $webhook->mediaurl = $request->input('MediaUrl0');
            }
            $webhook->type = $type;
            $webhook->date_sent = Carbon::now();
            $webhook->save();

            // $response = new Twiml();
            // $response->message('Thanks for your message.');
            // return response($response, 200)->header('Content-Type', 'application/xml');

        } catch (\Throwable $th) {
            \Log::info($th);
            $webhook = new CampaignReport();
            $webhook->type = '2';
            $webhook->error_message = $th->getMessage();
            $webhook->save();
            // $response = new Twiml();
            // $response->message($th->getMessage());
            // return response($response, 200)->header('Content-Type', 'application/xml');

            // return response($th->getMessage(), 500);
        }

    }
    public function inbound_email(Request $request)
    {
        try {
            $file = fopen(public_path('uploads/email.eml'), 'w');

            fwrite($file, print_r($_POST, true));
            fclose($file);
            $parser = new \PhpMimeMailParser\Parser();
            $path = public_path('uploads/email.eml');
            $parser->setText(file_get_contents($path));

            $attachments = $parser->getAttachments();
            $mediaurl = [];
            foreach ($attachments as $attachment) {
                $fname = $attachment->getFilename();
                $path = '/uploads/receive/' . $fname;
                $attachment->save(public_path('uploads/receive/'), \Parser::ATTACHMENT_DUPLICATE_SUFFIX);

                array_push($mediaurl, $path);
            }
            $imgUrl = implode(',', $mediaurl);
            $envelope = json_decode($request->input('envelope'), true);

            $to = $envelope['to'][0];
            $from = $envelope["from"];
            $subject = $parser->getHeader('subject');
            $campaignId = '';
            $fromContact = '';
            $toContact = 'HowCalm';
            $contact_id = '';

            if (strpos($subject, 'Re:') == 0) {
                $campaignSubject = ltrim($subject, 'Re: ');
                $campaignDetail = CampaignReport::where('subject', strval($campaignSubject))->where('toNumber', strval($from))->first();
                if ($campaignDetail) {
                    $campaignId = $campaignDetail->campaign_id;
                    $campaign = Campaign::whereId($campaignDetail->campaign_id)->first();

                    $contactId = $campaign ? explode(',', $campaign->recipient) : [];

                    foreach ($contactId as $key => $value) {
                        $contact = Contact::whereId($value)->where('contact_email', $from)->first();
                        if ($contact) {
                            $fromContact = $contact->contact_first_name;
                            $contact_id = $contact->id;
                        }
                    }
                }

            }
            CampaignReport::create([
                'campaign_id' => $campaignId,
                'status' => 'Incoming',
                'direction' => 'Inbound-api',
                'toNumber' => $to,
                'fromNumber' => $from,
                'fromContact' => $fromContact,
                'toContact' => $toContact,
                'contact_id' => $contact_id,
                'type' => '1',
                'subject' => $subject,
                'body' => $parser->getMessageBody('text'),
                'mediaurl' => $imgUrl,
                'date_sent' => Carbon::now(),
            ]);
            $file = fopen(public_path('uploads/email.eml'), 'w');

            fwrite($file, '');
            fclose($file);

        } catch (\Throwable $th) {
            \Log::info($th);
            $webhook = new CampaignReport();
            $webhook->error_message = $th->getMessage();
            $webhook->type = '1';
            $webhook->save();
        }

    }
}
