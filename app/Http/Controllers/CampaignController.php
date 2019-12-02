<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\CampaignReport;
use App\Contact;
use App\Group;
use App\Layout;
use App\Map;
use App\Taxonomy;
use App\User;
use DB;
use Illuminate\Http\Request;
use Session;

class CampaignController extends Controller
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

        // Here Get List of group [Start]
        $groupList = Group::pluck('group_name', 'group_name');
        $campaigns = Campaign::get();
        $groups = Group::get();

        // Here Get List of group [Start]

        return view('backEnd.campaign.index', compact('home', 'taxonomies', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'groupList', 'campaigns', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        // Here Get List of group [Start]
        $groupList = Group::pluck('group_name', 'id');

        // Here Get List of group [Start]

        // if(Session::get('imagePath') == null || Session::get('imagePath') == ''){
        //   $imagePath = Session::put('imagePath','');
        // }

        return view('backEnd.campaign.create', compact('home', 'taxonomies', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'imagePath', 'groupList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'group_id' => 'required',

        ]);
        if ($request->get('campaign_type') != 2 && $request->get('campaign_type') == 3) {
            $this->validate($request, [
                'audio_campaign_file' => 'required|file|mimes:audio/mpeg,mpga,mp3,wav,aac',
            ]);
        }

        try {
            DB::beginTransaction();
            if ($request->hasFile('audio_campaign_file')) {
                $imagePath = $this->upload($request->file('audio_campaign_file'), 'audio');
            } else if ($request->hasFile('attechment_campaign_file')) {
                $campaignImg = $request->file('attechment_campaign_file');
                $imagePath = $this->upload($request->file('attechment_campaign_file'), 'attachment');
            } else {
                $imagePath = '';
            }

            $seesionData = Session::get('imagePath');
            $groupName = '';
            if ($request->has('group_id')) {
                $groupName = implode(",", $request->get('group_id'));
            }
            $sending_status = 'sent';
            if ($request->sending_type == 2) {
                $sending_status = 'pending';
            }
            $insertCam = Campaign::create([
                'name' => $request->get('name'),
                'user_id' => '1',
                'campaign_type' => $request->get('campaign_type'),
                'campaign_file' => $imagePath,
                'subject' => $request->get('subject'),
                'group_id' => $groupName,
                'body' => $request->get('body'),
                'schedule_date' => date('Y-m-d h:i:s', strtotime($request->get('schedule_date'))),
                'status' => 1,
                'sending_type' => $request->get('sending_type'),
                'sending_status' => $sending_status,
            ]);

            //Get Latest Campaign Confirm
            $campaignConfirm = Campaign::where('id', $insertCam->id)->first();

            //Get Latest Campaign Confirm
            $groupName = explode(',', $campaignConfirm->group_id);

            $groupTemp = Group::get();
            $recipient = [];
            foreach ($groupName as $key => $id) {
                foreach ($groupTemp as $value) {
                    if ($value->id == $id) {
                        $groupContactList = $value->contact;
                        foreach ($groupContactList as $key => $valuenew) {
                            $groupContact[] = $valuenew;
                            $recipient[] = $valuenew->id;
                        }
                    }
                }
            }

            $getRecipient = $recipient != null ? implode(',', $recipient) : '';
            Campaign::where('id', $campaignConfirm->id)->update(['recipient' => $getRecipient]);

            DB::commit();
            if ($request->get('save_continue') == 'save') {
                return redirect()->back()->with('success', 'Campaign added successfully')->withInput();
            } else {
                return redirect()->to('confirm/' . $insertCam->id);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Some thing went wrong')->withInput();
        }

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
        //
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

        // Here Get List of group [Start]
        $groupList = Group::pluck('group_name', 'id');

        // Here Get List of group [Start]

        // if(Session::get('imagePath') == null || Session::get('imagePath') == ''){
        //   $imagePath = Session::put('imagePath','');
        // }
        $campaignConfirm = Campaign::where('id', $id)->first();
        $grouId = explode(',', $campaignConfirm->group_id);
        return view('backEnd.campaign.edit', compact('home', 'taxonomies', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'imagePath', 'groupList', 'campaignConfirm', 'grouId'));
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
        $this->validate($request, [
            'name' => 'required',
            'group_id' => 'required',

        ]);
        if ($request->get('campaign_type') != 2) {
            if ($request->get('campaign_type') == 3) {
                $this->validate($request, [
                    'audio_campaign_file' => 'required|file|mimes:audio/mpeg,mpga,mp3,wav,aac',
                ]);
            }
        }

        try {
            DB::beginTransaction();
            if ($request->hasFile('audio_campaign_file')) {
                $imagePath = $this->upload($request->file('audio_campaign_file'), 'audio');

            } else if ($request->hasFile('attechment_campaign_file')) {
                $campaignImg = $request->file('attechment_campaign_file');
                $imagePath = $this->upload($request->file('attechment_campaign_file'), 'attachment');
            } else {
                $imagePath = '';
            }

            $seesionData = Session::get('imagePath');
            $groupName = '';
            if ($request->has('group_id')) {
                $groupName = implode(",", $request->get('group_id'));
            }
            $schedule_date = null;
            if ($request->get('sending_type') == 2) {
                $schedule_date = date('Y-m-d', strtotime($request->get('schedule_date')));
            }
            $sending_status = 'sent';
            if ($request->sending_type == 2) {
                $sending_status = 'pending';
            }
            $insertCam = Campaign::where('id', $id)->update([
                'name' => $request->get('name'),
                'user_id' => '1',
                'campaign_type' => $request->get('campaign_type'),
                'campaign_file' => $imagePath,
                'subject' => $request->get('subject'),
                'group_id' => $groupName,
                'body' => $request->get('body'),
                'schedule_date' => date('Y-m-d h:i:s', strtotime($request->get('schedule_date'))),
                'sending_type' => $request->get('sending_type'),
                'sending_status' => $sending_status,
            ]);

            //Get Latest Campaign Confirm
            $campaignConfirm = Campaign::whereId($id)->first();

            //Get Latest Campaign Confirm
            $groupName = explode(',', $campaignConfirm->group_id);

            $groupTemp = Group::get();
            $recipient = [];
            foreach ($groupName as $key => $id) {
                foreach ($groupTemp as $value) {
                    if ($value->id == $id) {
                        $groupContactList = $value->contact;
                        foreach ($groupContactList as $key => $valuenew) {
                            $groupContact[] = $valuenew;
                            $recipient[] = $valuenew->id;
                        }
                    }
                }
            }

            $getRecipient = $recipient != null ? implode(',', $recipient) : '';
            Campaign::where('id', $campaignConfirm->id)->update(['recipient' => $getRecipient]);

            DB::commit();
            if ($request->get('save_continue') == 'save') {
                return redirect()->back()->with('success', 'Campaign updated successfully')->withInput();
            } else {
                return redirect()->to('confirm/' . $campaignConfirm->id);
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Something went wrong')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            Campaign::whereId($id)->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Campaign deleted successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function confirm($id)
    {
        //
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

        //Get Latest Campaign Confirm
        $campaignConfirm = Campaign::where('id', $id)->first();

        //Get Latest Campaign Confirm
        $recipient = $campaignConfirm->recipient != '' ? explode(',', $campaignConfirm->recipient) : null;
        $groupContact = [];
        if ($recipient != null) {
            foreach ($recipient as $key => $value) {
                $groupContact[] = Contact::whereId($value)->first();
            }
        }

        return view('backEnd.campaign.confirm', compact('home', 'taxonomies', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'campaignConfirm', 'groupContact'));
    }
    public function campaign_report($id)
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

        $campaign_report = CampaignReport::where('campaign_id', $id);
        $campaign_data = $campaign_report->get();

        $response = $campaign_data->where('direction', 'Inbound-api')->count();
        $delivered = $campaign_data->where('status', '!=', 'Incoming')->count();
        $campaign = Campaign::whereId($id)->first();
        $user = User::whereId($campaign->user_id)->first();

        return view('backEnd.campaign.campaign_report', compact('home', 'taxonomies', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'campaign_data', 'campaign', 'response', 'delivered', 'file_type', 'user'));
    }
    public function deleteRecipient(Request $request)
    {
        $id = $request->get('id');
        $campaignId = $request->get('campaignId');
        $campingData = Campaign::where('id', $campaignId)->first();
        $getRecipient = explode(',', $campingData->recipient);
        if (count($getRecipient) > 1) {

            foreach ($getRecipient as $key => $value) {

                if ($value == $id) {
                    array_splice($getRecipient, $key, 1);
                }
            }
            $implodeData = implode(',', $getRecipient);
            Campaign::where('id', $campaignId)->update(['recipient' => $implodeData]);

            return response()->json([
                'message' => 'successfully deleted.',
                'success' => true,
            ], 200);
        } else {
            return response()->json([
                'message' => 'No deleted.',
                'error' => true,
            ], 200);
        }

    }
    public function upload($image, $folder)
    {
        $tempname = explode('.', $image->getClientOriginalName());
        $tempname = str_replace(' ', '_', $tempname);
        $name = $tempname[0] . '_' . time() . '.' . $image->getClientOriginalExtension();
        $path = '/uploads/campaigns/' . $folder;
        $destinationPath = public_path($path);

        $imagePath = $destinationPath . "/" . $name;
        $image->move($destinationPath, $name);

        return $path . '/' . $name;
    }
    public function updateStatus(Request $request)
    {
        $id = $request->get('id');
        $getCampaignData = Campaign::where('id', $id)->first();
        if ($getCampaignData->status == 0) {
            Campaign::where('id', $id)->update(['status' => 1]);
        } else {
            Campaign::where('id', $id)->update(['status' => 0]);
        }
        return response()->json([
            'message' => 'successfully status updated.',
            'success' => true,
            'status' => $getCampaignData->status,
        ], 200);
    }
    public function deleteCampaigns(Request $request)
    {
        $id = $request->get('id');
        try {
            DB::beginTransaction();
            Campaign::whereId($id)->delete();
            DB::commit();
            return response()->json([
                'message' => 'successfully deleted.',
                'success' => true,

            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

}
