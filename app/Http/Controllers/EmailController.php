<?php

namespace App\Http\Controllers;

use App\CampaignReport;
use App\Contact;
use Carbon\Carbon;
use DB;
use Mail;
use Sentinel;

class EmailController extends Controller
{
    public function send_email($campaign)
    {
        try {
            if ($campaign) {
                $recipients = $campaign->recipient != '' ? explode(',', $campaign->recipient) : '';
                foreach ($recipients as $key => $value) {
                    $contact = Contact::whereId($value)->first();
                    if ($contact) {
                        $contact_email = $contact->contact_email;
                        // $contact_email = 'amit.d9ithub@gmail.com';

                        if ($contact_email != null) {
                            $from = env('MAIL_FROM_ADDRESS');
                            $name = env('MAIL_FROM_NAME');
                            $email = new \SendGrid\Mail\Mail();
                            $email->setFrom($from, $name);
                            $email->setSubject($campaign->subject);
                            $email->addTo($contact_email, $contact->contact_first_name);
                            $email->addContent("text/plain", $campaign->body);
                            if ($campaign->sending_type == 2) {
                                $time = strtotime($campaign->schedule_date);
                                $email->setSendAt($time);
                            }
                            if ($campaign->campaign_file != '') {
                                $filename = public_path($campaign->campaign_file);
                                $file_type = \File::extension($filename);
                                $content = file_get_contents($filename);
                                $content = base64_encode($content);
                                $attachment = new \SendGrid\Mail\Attachment();
                                $attachment->setContent($content);
                                $attachment->setType($file_type);
                                $attachment->setFilename(time() . '.' . $file_type);
                                $attachment->setDisposition("attachment");
                                $email->addAttachment($attachment);
                            }
                            $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
                            $response = $sendgrid->send($email);
                            DB::beginTransaction();
                            CampaignReport::create([
                                'user_id' => Sentinel::check()->id,
                                'type' => $campaign->campaign_type,
                                'status' => $response->statusCode() == 202 ? 'Delivered' : 'Undelivered',
                                'date_sent' => Carbon::now(),
                                'error_message' => $response->statusCode(),
                                'toContact' => $contact->contact_first_name,
                                'fromNumber' => env('MAIL_FROM_ADDRESS'),
                                'toNumber' => $contact_email,
                                'direction' => 'Outbound-api',
                                'fromContact' => 'HowCalm',
                                'contact_id' => $value,
                                'subject' => $campaign->subject,
                                'body' => $campaign->body,
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
