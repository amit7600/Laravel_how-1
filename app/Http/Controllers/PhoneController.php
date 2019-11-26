<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Phone;
use App\Servicephone;
use App\Locationphone;
use App\Airtables;
use App\Source_data;
use App\Services\Stringtoint;
use Maatwebsite\Excel\Facades\Excel;

class PhoneController extends Controller
{

    public function airtable()
    {

        Phone::truncate();
        $airtable = new Airtable(array(
            'api_key'   => env('AIRTABLE_API_KEY'),
            'base'      => env('AIRTABLE_BASE_URL'),
        ));

        $request = $airtable->getContent( 'phones' );

        do {


            $response = $request->getResponse();

            $airtable_response = json_decode( $response, TRUE );

            foreach ( $airtable_response['records'] as $record ) {

                $phone = new Phone();
                $strtointclass = new Stringtoint();
                $phone->phone_recordid = $record[ 'id' ];
                $phone->phone_recordid = $strtointclass->string_to_int($record[ 'id' ]);
                $phone->phone_number = isset($record['fields']['number'])?$record['fields']['number']:null;

                $phone->phone_extension = isset($record['fields']['extension'])?$record['fields']['extension']:null;

                $phone->phone_type = isset($record['fields']['type'])? implode(",", $record['fields']['type']):null;

                if(isset($record['fields']['office phone'])){
                    $i = 0;
                    foreach ($record['fields']['office phone']  as  $value) {

                        $phone_office=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $phone->phone_office = $phone->phone_office. ','. $phone_office;
                        else
                            $phone->phone_office = $phone_office;
                        $i ++;
                    }
                }

                if(isset($record['fields']['office fax'])){
                    $i = 0;
                    foreach ($record['fields']['office fax']  as  $value) {

                        $phone_office_fax=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $phone->phone_office_fax = $phone->phone_office_fax. ','. $phone_office_fax;
                        else
                            $phone->phone_office_fax = $phone_office_fax;
                        $i ++;
                    }
                }

                if(isset($record['fields']['emergency phone'])){
                    $i = 0;
                    foreach ($record['fields']['emergency phone']  as  $value) {

                        $phone_emergency=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $phone->phone_emergency = $phone->phone_emergency. ','. $phone_emergency;
                        else
                            $phone->phone_emergency = $phone_emergency;
                        $i ++;
                    }
                }

                if(isset($record['fields']['contacts'])){
                    $i = 0;
                    foreach ($record['fields']['contacts']  as  $value) {

                        $phone_contacts=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $phone->phone_contacts = $phone->phone_contacts. ','. $phone_contacts;
                        else
                            $phone->phone_contacts = $phone_contacts;
                        $i ++;
                    }
                }

                if(isset($record['fields']['language'])){
                    $i = 0;
                    foreach ($record['fields']['language']  as  $value) {

                        $phone_language=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $phone->phone_language = $phone->phone_language. ','. $phone_language;
                        else
                            $phone->phone_language = $phone_language;
                        $i ++;
                    }
                }

                $phone->phone_description = isset($record['fields']['description'])?$record['fields']['description']:null;

                $phone->phone_id = isset($record['fields']['id'])?$record['fields']['id']:null;

                if(isset($record['fields']['locations'])){
                    $i = 0;
                    foreach ($record['fields']['locations']  as  $value) {

                        $phonelocation=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $phone->phone_locations = $phone->phone_locations. ','. $phonelocation;
                        else
                            $phone->phone_locations = $phonelocation;
                        $i ++;
                    }
                }


                if(isset($record['fields']['services'])){
                    $i = 0;
                    foreach ($record['fields']['services']  as  $value) {

                        $phoneservice=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $phone->phone_services = $phone->phone_services. ','. $phoneservice;
                        else
                            $phone->phone_services = $phoneservice;
                        $i ++;
                    }
                }

                if(isset($record['fields']['organizations'])){
                    $i = 0;
                    foreach ($record['fields']['organizations']  as  $value) {

                        $phoneorganization=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $phone->phone_organizations = $phone->phone_organizations. ','. $phoneorganization;
                        else
                            $phone->phone_organizations = $phoneorganization;
                        $i ++;
                    }
                }

                if(isset($record['fields']['details'])){
                    $i = 0;
                    foreach ($record['fields']['details']  as  $value) {

                        $phone_details=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $phone->phone_details = $phone->phone_details. ','. $phone_details;
                        else
                            $phone->phone_details = $phone_details;
                        $i ++;
                    }
                }

                if(isset($record['fields']['schedule'])){
                    $i = 0;
                    foreach ($record['fields']['schedule']  as  $value) {

                        $phone_schedule=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $phone->phone_schedule = $phone->phone_schedule. ','. $phone_schedule;
                        else
                            $phone->phone_schedule = $phone_schedule;
                        $i ++;
                    }
                }

                $phone ->save();

            }
            
        }
        while( $request = $response->next() );

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Phones')->first();
        $airtable->records = Phone::count();
        $airtable->syncdate = $date;
        $airtable->save();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $phones = Phone::orderBy('phone_recordid')->paginate(20);
        $source_data = Source_data::find(1);

        return view('backEnd.tables.tb_phones', compact('phones', 'source_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $phone= Phone::find($id);
        return response()->json($phone);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $phone = Phone::find($id);
        $phone->phone_number = $request->phone_number;
        $phone->phone_extension = $request->phone_extension;
        $phone->phone_type = $request->phone_type;
        $phone->phone_language = $request->phone_language;
        $phone->phone_description = $request->phone_description;
        $phone->phone_id = $request->phone_id;
        $phone->flag = 'modified';
        $phone->save();

        return response()->json($phone);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
