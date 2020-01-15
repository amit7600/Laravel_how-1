<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\CampaignReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Twilio\Twiml;

// use Twilio\TwiML\VoiceResponse;

class IvrController extends Controller
{
    public function __construct()
    {
        $this->_thankYouMessage = 'Thank you for calling the ET Phone Home' .
            ' Service - the adventurous alien\'s first choice' .
            ' in intergalactic travel.';

    }

    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function showWelcome(Request $request)
    {

        try {
            $response = new Twiml();
            // $gather = $response->gather(
            //     [
            //         'numDigits' => 1,
            //         'action' => '/ivr/menu-response',
            //         'method' => 'GET',
            //     ]
            // );

            // $gather->say(
            //     'hello , devin ' .
            //     'Thanks for calling the E T Phone Home Service. ' .
            //     'Please press 1 for directions. Press 2 for a ' .
            //     'list of planets to call.',
            //     ['loop' => 3]
            // );
            $gather = $response->gather(['action' => route('menu-response'), 'method' => 'GET']);

            $from = $request->input('From');
            $campaignDetail = CampaignReport::where('toNumber', $from)->where('type', 3)->orderBy('id', 'desc')->first();

            $media = '';
            if ($campaignDetail) {
                $campaignId = $campaignDetail->campaign_id;
                $campaign = Campaign::whereId($campaignDetail->campaign_id)->first();
                if ($campaign) {
                    $media = url($campaign->campaign_file);
                }
                if ($media != '') {
                    $gather->play($media);
                } else {
                    $gather->say('No media found');
                }

            } else {
                $gather->say('Your number is not added in any campaign');
            }

            return $response;

        } catch (\Throwable $th) {
            \Log::info($th->getMessage());
        }

    }

    /**
     * Responds to selection of an option by the caller
     *
     * @return \Illuminate\Http\Response
     */
    public function showMenuResponse(Request $request)
    {

        $selectedOption = $request->input('Digits');
        $from = $request->input('From');
        $toContact = 'HowCalm';

        $campaignDetail = CampaignReport::where('toNumber', $from)->where('type', 3)->orderBy('id', 'desc')->first();

        if ($campaignDetail) {
            $webhook = new CampaignReport();
            $webhook->body = $selectedOption;
            $webhook->status = 'Incoming';
            $webhook->fromContact = '';
            $webhook->toContact = $toContact;
            $webhook->contact_id = $campaignDetail->contact_id;

            if ($campaignDetail != null) {
                $webhook->campaign_id = $campaignDetail->campaign_id;
            }
            $webhook->direction = 'Inbound-api';
            $webhook->toNumber = $request->input('To');
            $webhook->fromNumber = $request->input('From');
            $type = 3;
            if ($request->input('MediaUrl0') != null) {
                // $type = 4;
                $webhook->mediaurl = $request->input('MediaUrl0');
            }
            $webhook->type = $type;
            $webhook->date_sent = Carbon::now();
            $webhook->save();

        }

        $response = new Twiml();
        $response->say(
            $this->_thankYouMessage,
            ['voice' => 'Alice', 'language' => 'en-US']
        );
        // $response->redirect(route('welcome', [], false));

        return $response;
    }

    /**
     * Responds with a <Dial> to the caller's planet
     *
     * @return \Illuminate\Http\Response
     */
    public function showPlanetConnection(Request $request)
    {
        $response = new Twiml();
        $response->say(
            $this->_thankYouMessage,
            ['voice' => 'Alice', 'language' => 'en-US']
        );
        $response->say(
            "You'll be connected shortly to your planet",
            ['voice' => 'Alice', 'language' => 'en-US']
        );

        $planetNumbers = [
            '2' => '+12024173378',
            '3' => '+12027336386',
            '4' => '+12027336637',
        ];
        $selectedOption = $request->input('Digits');

        $planetNumberExists = isset($planetNumbers[$selectedOption]);

        if ($planetNumberExists) {
            $selectedNumber = $planetNumbers[$selectedOption];
            $response->dial($selectedNumber);

            return $response;
        } else {
            $errorResponse = new Twiml();
            $errorResponse->say(
                'Returning to the main menu',
                ['voice' => 'Alice', 'language' => 'en-US']
            );
            $errorResponse->redirect(route('welcome', [], false));

            return $errorResponse;
        }

    }

    /**
     * Responds with instructions to mothership
     * @return Services_Twilio_Twiml
     */
    private function _getReturnInstructions()
    {
        $response = new Twiml();
        $response->say(
            'To get to your extraction point, get on your bike and go down the' .
            ' street. Then Left down an alley. Avoid the police cars. Turn left' .
            ' into an unfinished housing development. Fly over the roadblock. Go' .
            ' passed the moon. Soon after you will see your mother ship.',
            ['voice' => 'Alice', 'language' => 'en-US']
        );
        $response->say(
            $this->_thankYouMessage,
            ['voice' => 'Alice', 'language' => 'en-US']
        );

        $response->hangup();

        return $response;
    }

    /**
     * Responds with instructions to choose a planet
     * @return Services_Twilio_Twiml
     */
    private function _getPlanetsMenu()
    {
        $response = new Twiml();
        $gather = $response->gather(
            ['numDigits' => '1', 'action' => '/ivr/planet-connection',
                'method' => 'GET']
        );
        $gather->say(
            'To call the planet Brodo Asogi, press 2. To call the planet' .
            ' Dugobah, press 3. To call an Oober asteroid to your location,' .
            ' press 4. To go back to the main menu, press the star key',
            ['voice' => 'Alice', 'language' => 'en-US']
        );

        return $response;
    }
}

