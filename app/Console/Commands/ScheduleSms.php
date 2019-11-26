<?php

namespace App\Console\Commands;

use App\Campaign;
use App\CampaignReport;
use App\Contact;
use App\Phone;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Sentinel;
use Twilio\Rest\Client;

class ScheduleSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends out schedule';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        print_r("Schedule Daemon Started \n");
        while (true) {
            $account_sid = getenv('TWILIO_SID');
            $account_token = getenv("TWILIO_TOKEN");
            $sending_number = getenv("TWILIO_FROM");
            $twilio_client = new Client($account_sid, $account_token);
            $now = Carbon::now()->toDateTimeString();
            $campaign = Campaign::where([['schedule_date', '=', $now], ['sending_status', 'pending'], ['sending_type', '2']])->first();
            print(1);
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
                                'user_id' => Sentinel::User()->id,
                                'type' => $campaign->campaign_type,
                                'status' => $data->status,
                                'date_sent' => $data->date_sent,
                                'direction' => $data->direction,
                                'toNumber' => $data->to,
                                'toContact' => $contact->contact_first_name,
                                'fromNumber' => $data->from,
                                'fromContact' => 'HowCalm',
                                'body' => $data->body,
                                'campaign_id' => $campaign->id,
                            ]);
                            DB::commit();
                        }
                    }
                }
                return redirect()->to('/campaigns')->with('success', 'Campaign send successfully!');

            }

            \sleep(1);
        }

    }
}
