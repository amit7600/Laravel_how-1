<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Map;
use Geolocation;
use Geocode;
use Spatie\Geocoder\Geocoder;
use App\Location;
use App\Address;
use Image;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Validator;
use Sentinel;
use Route;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;

class MapController extends Controller
{
    /**
     * Post Repository
     *
     * @var Post
    //  */
    // protected $about;

    // public function __construct(About $about)
    // {
    //     $this->about = $about;
    // }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $map = Map::find(1);
        $ungeocoded_location_numbers = Location::whereNull('location_latitude')->count();
        $invalid_location_info_count = Location::whereNull('location_name')->count();

        $geocode_status_text = '';
        if ($ungeocoded_location_numbers == $invalid_location_info_count) {
            $geocode_status_text = 'All valid locations have already been geocoded.';
        }

        $location_count = Location::whereNotNull('location_name')->count();

        return view('backEnd.pages.map', compact('map', 'ungeocoded_location_numbers', 'invalid_location_info_count', 'location_count', 'geocode_status_text'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {   
        $map=Map::find(1);
        $map->active=$request->active;
        $map->api_key=$request->api_key;
        $map->state=$request->state;
        $map->lat=$request->lat;
        $map->long=$request->long;
        $map->zoom=$request->zoom;       
        $map->zoom_profile=$request->profile_zoom;

        $map->save();

        Session::flash('message', 'Map updated!');
        Session::flash('status', 'success');

        return redirect('apis');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $post = $this->post->find($id);

        if (is_null($post))
        {
            return Redirect::route('posts.index');
        }

        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
    	$map=Map::find(1);
        // var_dump($request->input('active'));
        // exit();
        if ($request->input('active') == 'checked')
        {
            $map->active = 1;
            $map->api_key=$request->input('api_key');
            $map->state=$request->input('state');
            $map->lat=$request->input('lat');
            $map->long=$request->input('long');
            $map->zoom=$request->input('zoom');
            $map->zoom_profile=$request->input('profile_zoom');
        }
        else {
            $map->active = 0;
        }
        
        $map->save();

        Session::flash('message', 'Map updated!');
        Session::flash('status', 'success');

        return redirect('map');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->post->find($id)->delete();

        return Redirect::route('admin.posts.index');
    }

    public function upload()
    {
        $file = Input::file('file');
        $input = array('image' => $file);
        $rules = array(
            'image' => 'image'
        );
        $validator = Validator::make($input, $rules);
        if ( $validator->fails()) {
            return Response::json(array('success' => false, 'errors' => $validator->getMessageBag()->toArray()));
        }

        $fileName = time() . '-' . $file->getClientOriginalName();
        $destination = public_path() . '/uploads/';
        $file->move($destination, $fileName);

        echo url('/uploads/'. $fileName);
    }

    public function scan_ungeocoded_location(Request $request) {
        $map = Map::find(1);
        $geocoding_status = 'Not Started';
        $ungeocoded_location_numbers = Location::whereNull('location_latitude')->count();
        return view('backEnd.pages.map', compact('map', 'ungeocoded_location_numbers', 'geocoding_status'));
    }

    public function apply_geocode(Request $request) {
        $ungeocoded_location_info_list = Location::whereNull('location_latitude')->get();
        $client = new \GuzzleHttp\Client();
        $geocoder = new Geocoder($client);
        $geocode_api_key = env('GEOCODE_GOOGLE_APIKEY');
        $geocoder->setApiKey($geocode_api_key);

        foreach ($ungeocoded_location_info_list as $key => $location_info) {
            $location_name = $location_info->location_name;
            if (!is_null($location_name)) {
                $response = $geocoder->getCoordinatesForAddress($location_name);
                $latitude = $response['lat'];
                $longitude = $response['lng'];
                $location_info->location_latitude = $latitude;
                $location_info->location_longitude = $longitude;
                $location_info->save();
            }
        }

        return redirect('map');
    }

    public function apply_enrich(Request $request) {
        $valid_location_list = Location::whereNotNull('location_address')->get();
        foreach ($valid_location_list as $key => $valid_location) {
            $address = Address::where('address_recordid', '=', $valid_location->location_address)->first();
            $borough = $address->address_city;
            $house_address_info = explode(' ', $address->address_1);
            $house_number = $house_address_info[0];
            $street = $house_address_info[1];
            $app_id = 'b985eb41';
            $app_key = '9e7522143dca2c6347306d73882b6e3f';
            // $request_url = '/v1/address.json?houseNumber=314&street=west 100 st&borough=manhattan&app_id=abc123&app_key=def456';
            $request_url = 'https://api.cityofnewyork.us/geoclient/v1/address.json?houseNumber='.$house_number.'&street='.$street.' st&borough='.$borough.'&app_id='.$app_id.'&app_key='.$app_key;

            // var_dump($request_url);

            $json = file_get_contents($request_url);
            $obj = json_decode($json);
            var_dump($obj);

            // $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, 'https://api.cityofnewyork.us/geoclient/v1/address.json?houseNumber=30-61&street=87th st&borough=Queens&app_id=b985eb41&app_key=9e7522143dca2c6347306d73882b6e3f' );

            // curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            // // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            // //     'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            // //     'accept-encoding: gzip, deflate, br',
            // //     'accept-language: en-US,en;q=0.9',
            // //     'cache-control: max-age=0',
            // //     'content-length: 205',
            // //     'Content-Type: application/x-www-form-urlencoded',
            // //     'cookie:JSESSIONID=60A2AF9288C2D9712A3DB1D18FFE48DC.ims_64; amlbcookie=03; atidvisitor=%7B%22name%22%3A%22atidvisitor%22%2C%22val%22%3A%7B%22vrn%22%3A%22-592419-%22%7D%2C%22options%22%3A%7B%22path%22%3A%22%2F%22%2C%22session%22%3A15724800%2C%22end%22%3A15724800%7D%7D; AMAuthCookie=AQIC5wM2LY4SfcyWBWJN6gLWTBPLmFEW4dqk9DS4ndKQcoA.*AAJTSQACMDIAAlNLABM4NDU3NzQ4NzgzNDYyNzM4OTg5AAJTMQACMDM.*',
            // //     'origin: https://ims.wsecure.schneider-electric.com',
            // //     'referer: https://ims.wsecure.schneider-electric.com/opensso/UI/Login?errorMessage=auth.failed&errorMessage=auth.failed',
            // //     'upgrade-insecure-requests: 1',
            // //     'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36'
            // // ));
            // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_HEADER, false);
            // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            //         "Content-Type: application/json"
            //     ));
            // $data = json_decode(curl_exec($ch));
            // curl_close($ch);
            // var_dump($data);
            exit;
        }
    }
}
