<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Area;
use App\Airtables;
use App\Services\Stringtoint;
use Maatwebsite\Excel\Facades\Excel;

class AreaController extends Controller
{

    public function airtable()
    {

        Area::truncate();
        $airtable = new Airtable(array(
            'api_key'   => env('AIRTABLE_API_KEY'),
            'base'      => env('AIRTABLE_BASE_URL'),
        ));

        $request = $airtable->getContent( 'taxonomy' );

        do {


            $response = $request->getResponse();

            $airtable_response = json_decode( $response, TRUE );

            foreach ( $airtable_response['records'] as $record ) {

                $area = new Area();
                $strtointclass = new Stringtoint();

                $area->area_recordid = $strtointclass->string_to_int($record[ 'id' ]);
                 // $taxonomy->taxonomy_recordid = $record[ 'id' ];
                $area->area_name = isset($record['fields']['name'])?$record['fields']['name']:null;

                $area->area_id = isset($record['fields']['id'])?$record['fields']['id']:null;


                if(isset($record['fields']['services'])){
                    $i = 0;
                    foreach ($record['fields']['services']  as  $value) {

                        $area_services=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $area->area_services = $area->area_services. ','. $area_services;
                        else
                            $area->area_services = $area_services;
                        $i ++;
                    }
                } 

                $area->description = isset($record['fields']['description'])?$record['fields']['description']:null;

                $area ->save();

            }
            
        }
        while( $request = $response->next() );

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Service_area')->first();
        $airtable->records = Area::count();
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
        $areas = Area::orderBy('area_name')->paginate(20);

        return view('backEnd.tables.tb_area', compact('areas'));
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
        $area= Area::find($id);
        return response()->json($area);
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
        $area = Area::find($id);
        $area->area_name = $request->area_name;
        $area->area_id = $request->area_id;
        $area->area_description = $request->area_description;
        $area->save();

        return response()->json($area);
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
