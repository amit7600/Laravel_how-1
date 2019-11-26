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

                        if ($contact_email != null) {
                            $from = 'howcalm@sarapis.org';
                            $name = 'Devin Balkind';
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
                                // 'fromNumber' => $data->from,
                                'fromContact' => 'HowCalm',
                                'body' => $campaign->body,
                                'campaign_id' => $campaign->id,
                            ]);
                            DB::commit();
                        }

                    }
                }
                return;
            } else {
                return;
            }

        } catch (\Throwable $th) {
            return $th;

        }

    }
}
