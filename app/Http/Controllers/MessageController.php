<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\CampaignReport;
use App\Layout;
use App\Map;
use App\Taxonomy;
use DB;
use Illuminate\Http\Request;
use Sentinel;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $home = Layout::find(1);
        $map = Map::find(1);
        $taxonomies = Taxonomy::where('taxonomy_parent_name', '=', null)->orderBy('taxonomy_name', 'asc')->get();
        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        $checked_insurances = [];
        $checked_ages = [];
        $checked_languages = [];
        $checked_settings = [];
        $checked_culturals = [];
        $checked_transportations = [];
        $checked_hours = [];

        $getCampaignReport = CampaignReport::orderBy('id', 'DESC')->get();

        $campaign_name = Campaign::pluck('name', 'name');
        $campaignDetail = Campaign::pluck('name', 'id');

        return view('backEnd.messages.index', compact('home', 'taxonomies', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'getCampaignReport', 'campaign_name', 'campaignDetail'));

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
        //
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
        //
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
    public function messages_sent()
    {
        try {
            $home = Layout::find(1);
            $map = Map::find(1);
            $taxonomies = Taxonomy::where('taxonomy_parent_name', '=', null)->orderBy('taxonomy_name', 'asc')->get();
            $parent_taxonomy = [];
            $child_taxonomy = [];
            $checked_organizations = [];
            $checked_insurances = [];
            $checked_ages = [];
            $checked_languages = [];
            $checked_settings = [];
            $checked_culturals = [];
            $checked_transportations = [];
            $checked_hours = [];

            $campaignDetail = Campaign::pluck('name', 'id');

            $getCampaignReport = CampaignReport::where('direction', 'outbound-api')->orderBy('id', 'DESC')->get();
            $campaign_name = Campaign::pluck('name', 'name');

            return view('backEnd.messages.index', compact('home', 'taxonomies', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'getCampaignReport', 'campaign_name', 'campaignDetail'));

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('success', $th->getMessage());
        }

    }
    public function messages_recieved()
    {
        try {
            $home = Layout::find(1);
            $map = Map::find(1);
            $taxonomies = Taxonomy::where('taxonomy_parent_name', '=', null)->orderBy('taxonomy_name', 'asc')->get();
            $parent_taxonomy = [];
            $child_taxonomy = [];
            $checked_organizations = [];
            $checked_insurances = [];
            $checked_ages = [];
            $checked_languages = [];
            $checked_settings = [];
            $checked_culturals = [];
            $checked_transportations = [];
            $checked_hours = [];

            $campaignDetail = Campaign::pluck('name', 'id');

            $getCampaignReport = CampaignReport::where('direction', 'Inbound-api')->orderBy('id', 'DESC')->get();

            $campaign_name = Campaign::pluck('name', 'name');

            return view('backEnd.messages.index', compact('home', 'taxonomies', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'getCampaignReport', 'campaign_name', 'campaignDetail'));

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function connect_compaign(Request $request)
    {
        try {
            DB::beginTransaction();
            $campaignReportId = $request->get('id');
            $campaignId = $request->get('campaignId');

            if ($request->has('id')) {
                foreach ($campaignReportId as $key => $value) {
                    CampaignReport::whereId($value)->update([
                        'campaign_id' => $campaignId,
                        'user_id' => Sentinel::check()->id,
                    ]);
                }
            }

            DB::commit();
            return redirect()->to('messages')->with('success', 'Campaign added successfully');

            // return response()->json([
            //     'message' => 'Campaign added successfully!',
            //     'success' => true,
            // ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->to('messages')->with('error', $th->getMessage());

            // return response()->json([
            //     'message' => $th->getMessage(),
            //     'success' => false,
            // ], 500);

        }
    }
}
