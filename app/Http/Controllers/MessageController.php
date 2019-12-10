<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\CampaignReport;
use App\Contact;
use App\Group;
use App\Layout;
use App\Map;
use App\Organization;
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
            $contactId = $request->get('id');
            $groupId = $request->get('groupId');
            if ($request->has('id')) {
                foreach ($contactId as $key => $value) {
                    $contact = Contact::whereId($value)->first();
                    if ($contact) {
                        $groups = $contact->contact_group != null ? explode(',', $contact->contact_group) : [];
                        $checkValue = in_array($groupId, $groups);
                        if ($checkValue == false) {
                            array_push($groups, $groupId);
                        }
                        $groupData = implode(', ', $groups);
                        Contact::whereId($contact->id)->update([
                            'contact_group' => $groupData,
                        ]);
                    }
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
    public function getContact(Request $request)
    {
        try {
            DB::beginTransaction();
            $campaignReportId = $request->get('id');

            $phone_recordid = [];
            $contacts = [];
            if ($request->has('id')) {
                // $contact = Contact::where
                $phones = Phone::get();

                foreach ($campaignReportId as $key => $value) {
                    $campaignDetail = CampaignReport::whereId($value)->first();

                    $fromNumberSms = substr($campaignDetail->fromNumber, 2);
                    // for sms
                    $temp = [];
                    if ($campaignDetail->type == 2) {
                        foreach ($phones as $key => $phone) {
                            $phone_number = str_replace('-', '', $phone->phone_number);
                            $phone_number = preg_replace('/[^A-Za-z0-9\-]/', '', $phone_number);
                            if ($phone_number == $fromNumberSms || strpos($phone_number, $fromNumberSms)) {
                                array_push($temp, 1);
                                $checkValue = in_array($phone->phone_recordid, $phone_recordid);
                                if ($checkValue == false) {
                                    array_push($phone_recordid, $phone->phone_recordid);
                                }
                            }
                        }
                        if (count($temp) == 0 || $temp == null) {

                            $phoneId = $this->addContact($campaignDetail->fromNumber, '');
                            array_push($phone_recordid, $phoneId);

                        }

                        // for email
                    }
                    if ($campaignDetail->type == 1) {
                        $emailContact = Contact::where('contact_email', $campaignDetail->fromNumber)->get();
                        if (count($emailContact) > 0) {
                            foreach ($emailContact as $key => $contact) {
                                array_push($contacts, $contact);
                            }
                        } else {
                            $contactId = $this->addContact('', $campaignDetail->fromNumber);
                            $contact = Contact::where('contact_cell_phones', strval($contactId))->first();

                            array_push($contacts, $contact);
                        }

                    }
                }
            }

            // sleep(10);
            $allContact = [];
            // push phone number data
            foreach ($phone_recordid as $key => $value) {
                $contact = Contact::where('contact_cell_phones', strval($value))->first();
                // dd($contact);
                array_push($allContact, $contact);
            }
            //  push email contact data
            foreach ($contacts as $key => $contact) {
                $checkValue = in_array($contact, $allContact);
                if ($checkValue == false) {
                    array_push($allContact, $contact);
                }
            }
            // get require data
            $finalArray = [];
            foreach ($allContact as $key => $data) {
                $phoneData = Phone::where('phone_recordid', $data->contact_cell_phones)->first();
                $organizationData = Organization::where('organization_recordid', $data->contact_organizations)->first();
                $id = $data->id;
                $name = $data->contact_first_name . ' ' . $data->contact_last_name;
                $email = $data->contact_email != null ? $data->contact_email : '';
                $phone = $phoneData ? $phoneData->phone_number : '';
                $organization = $organizationData ? $organizationData->organization_name : '';

                array_push($finalArray, array('id' => $id, 'name' => $name, 'email' => $email, 'phone' => $phone, 'organization' => $organization));
            }
            return response()->json([
                'data' => $finalArray,
                'success' => true,
            ], 200);

        } catch (\Throwable $th) {
            dd($th);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);

        }
    }
    public function addContact($phone_number, $email)
    {
        $phone_recordids = Phone::select("phone_recordid")->distinct()->get();
        $phone_recordid_list = array();
        foreach ($phone_recordids as $key => $value) {
            $phoneId = $value->phone_recordid;
            array_push($phone_recordid_list, $phoneId);
        }
        $phone_recordid_list = array_unique($phone_recordid_list);

        $new_recordid = Phone::max('phone_recordid') + 1;
        if (in_array($new_recordid, $phone_recordid_list)) {
            $new_recordid = Phone::max('phone_recordid') + 1;
        }
        $newPhone = Phone::create([
            'phone_recordid' => $new_recordid,
            'phone_number' => $phone_number,
            'phone_type' => "cell phone",
        ]);
        DB::commit();

// save contact
        $contact_recordids = Contact::select("contact_recordid")->distinct()->get();
        $contact_recordid_list = array();
        foreach ($contact_recordids as $key => $value) {
            $contact_recordid = $value->contact_recordid;
            array_push($contact_recordid_list, $contact_recordid);
        }
        $contact_recordid_list = array_unique($contact_recordid_list);

        $contact_recordid = Contact::max('contact_recordid') + 1;
        if (in_array($contact_recordid, $contact_recordid_list)) {
            $contact_recordid = Contact::max('contact_recordid') + 1;
        }

        $contact = Contact::create([
            'contact_recordid' => $contact_recordid,
            'contact_cell_phones' => $new_recordid,
            'contact_email' => $email,
            'flag' => 'generated contact',
        ]);
        DB::commit();

        return $new_recordid;

    }
}
