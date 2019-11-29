<?php

namespace App\Http\Controllers;

use App\CampaignReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Twilio\Twiml;

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
            $webhook = new CampaignReport();
            $webhook->body = $sms_body;
            $webhook->status = 'Incoming';
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
            $webhook = new CampaignReport();
            $webhook->error_message = $th->getMessage();
            $webhook->save();
            $response = new Twiml();
            $response->message($th->getMessage());
            return response($response, 200)->header('Content-Type', 'application/xml');

            // return response($th->getMessage(), 500);
        }

    }
    public function inbound_email(Request $request)
    {
        try {
            $file = fopen(public_path('uploads/email.txt'), 'w');

            fwrite($file, print_r($_POST, true));
            fclose($file);

            $mailParser = new \ZBateson\MailMimeParser\MailMimeParser();
            $handle = fopen(public_path('uploads/email.txt'), 'r');

            $message = $mailParser->parse($handle);
            fclose($handle);

            $envelope = json_decode($request->input('envelope'), true);

            $to = $envelope['to'][0];
            $from = $envelope["from"];
            $subject = $request->input("subject");
            CampaignReport::create([
                'status' => 'Incoming',
                'direction' => 'Inbound-api',
                'toNumber' => $to,
                'fromNumber' => $from,
                'type' => '1',
                'body' => $message->getTextContent(),
                'date_sent' => Carbon::now(),
            ]);
            $file = fopen(public_path('uploads/email.txt'), 'w');

            fwrite($file, '');
            fclose($file);

        } catch (\Throwable $th) {
            \Log::info($th->getMessage());
            $webhook = new CampaignReport();
            $webhook->error_message = $th->getMessage();
            $webhook->save();
        }

    }
}
