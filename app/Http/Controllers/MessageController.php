<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\CampaignReport;
use App\Contact;
use App\Group;
use App\Layout;
use App\Map;
use App\Phone;
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
        $GroupDetail = Group::where('group_type', 'Static')->pluck('group_name', 'group_recordid');

        return view('backEnd.messages.index', compact('home', 'taxonomies', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'getCampaignReport', 'campaign_name', 'campaignDetail', 'GroupDetail'));

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
    public function connect_group(Request $request)
    {
        try {
            DB::beginTransaction();
            $campaignReportId = $request->get('id');
            $groupId = $request->get('groupId');
            if ($request->has('id')) {
                // $contact = Contact::where
                $phones = Phone::get();

                $phone_recordid = '';
                foreach ($campaignReportId as $key => $value) {
                    $campaignDetail = CampaignReport::whereId($value)->first();
                    $fromNumberSms = substr($campaignDetail->fromNumber, 2);
                    // for sms
                    foreach ($phones as $key => $phone) {
                        $phone_number = str_replace('-', '', $phone->phone_number);
                        $phone_number = preg_replace('/[^A-Za-z0-9\-]/', '', $phone_number);
                        if ($phone_number == $fromNumberSms) {
                            $phone_recordid = $phone->phone_recordid;
                        }
                    }
                    // for email
                    if ($campaignDetail->type == 1) {
                        $emailContact = Contact::where('contact_email', $campaignDetail->fromNumber)->first();
                        if ($emailContact) {
                            $groups = $emailContact->contact_group != null ? explode(',', $emailContact->contact_group) : [];

                            $checkValue = in_array($groupId, $groups);
                            // dd($checkValue);
                            if ($checkValue == false) {
                                array_push($groups, $groupId);
                            } else {
                                return response()->json([
                                    'message' => 'Group already exist!',
                                    'success' => true,
                                ], 200);
                            }

                            $groupData = implode(',', $groups);
                            Contact::whereId($emailContact->id)->update([
                                'contact_group' => $groupData,
                            ]);

                        }
                    }
                }

            }
            // for incoming sms only
            if ($phone_recordid != null) {

                $contact = Contact::where('contact_cell_phones', $phone_recordid)->first();
                if ($contact) {
                    $groups = $contact->contact_group != null ? explode(',', $contact->contact_group) : [];
                    $checkValue = in_array($groupId, $groups);
                    if ($checkValue == false) {
                        array_push($groups, $groupId);
                    } else {
                        return response()->json([
                            'message' => 'Group already exist!',
                            'success' => true,
                        ], 200);
                    }
                    $groupData = implode(',', $groups);
                    Contact::whereId($contact->id)->update([
                        'contact_group' => $groupData,
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Group added successfully!',
                'success' => true,
            ], 200);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
                'success' => true,
            ], 200);

        }
    }
}
