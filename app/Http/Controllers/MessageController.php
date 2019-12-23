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
use Carbon\Carbon;
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
            $group_tag = $request->get('tokenfield');
            $phone_recordid = [];
            if ($request->has('newContactData')) {
                $campaignId = $request->get('newContactData');
                foreach ($campaignId as $key => $value) {
                    $campaignDetail = CampaignReport::whereId($value)->first();
                    if ($campaignDetail->type == 1) {
                        $phoneId = $this->addContact('', $campaignDetail->fromNumber);
                        array_push($phone_recordid, $phoneId);

                    } elseif ($campaignDetail->type == 2) {
                        $phoneId = $this->addContact($campaignDetail->fromNumber, '');
                        array_push($phone_recordid, $phoneId);
                    }
                }
                foreach ($phone_recordid as $key => $phoneId) {
                    $contact = Contact::where('contact_cell_phones', strval($phoneId))->first();
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
                        DB::commit();

                    }
                }

            }
            $group_contact_list = Contact::where('contact_group', 'LIKE', '%' . $groupId . '%')->get();

            Group::where('group_recordid', $groupId)->update([
                'group_members' => count($group_contact_list),
                'group_tag' => $group_tag,
            ]);
            DB::commit();

            return response()->json([
                'message' => 'Group added successfully!',
                'success' => true,
            ], 200);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false,
            ], 500);

        }
    }
    public function getContact(Request $request)
    {
        try {
            DB::beginTransaction();
            $campaignReportId = $request->get('id');

            $phone_recordid = [];
            $contacts = [];
            $newRecordId = [];
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
                            array_push($newRecordId, $campaignDetail->id);
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
                            // $contactId = $this->addContact('', $campaignDetail->fromNumber);
                            // $contact = Contact::where('contact_cell_phones', strval($contactId))->first();
                            array_push($newRecordId, $campaignDetail->id);
                        }

                    }
                }
            }

            // sleep(10);
            $allContact = [];
            // push phone number data
            foreach ($phone_recordid as $key => $value) {
                $contact = Contact::where('contact_cell_phones', strval($value))->first();
                if ($contact) {
                    array_push($allContact, $contact);
                }

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
                'campaignId' => $newRecordId,
                'success' => true,
            ], 200);

        } catch (\Throwable $th) {
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
            'contact_tag' => 'generated contact',
        ]);
        DB::commit();

        return $new_recordid;

    }
    public function messagesSetting()
    {
        $twillioSid = env('TWILIO_SID');
        $twillioKey = env('TWILIO_TOKEN');
        $twllioNumber = env('TWILIO_FROM');
        $sendgridKey = env('SENDGRID_API_KEY');

        return view('backEnd.messages.messageSetting', compact('twillioSid', 'twillioKey', 'twllioNumber', 'sendgridKey'));

    }
    public function saveMessageCredential(Request $request)
    {
        $this->validate($request, [
            'twillioSid' => 'required',
            'twillioKey' => 'required',
            'twillioNumber' => 'required',
            'sendgridApiKey' => 'required',
        ]);

        try {

            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);
            $values = [
                "TWILIO_SID" => $request->get('twillioSid'),
                "TWILIO_TOKEN" => $request->get('twillioKey'),
                "TWILIO_FROM" => $request->get('twillioNumber'),
                "SENDGRID_API_KEY" => $request->get('sendgridApiKey'),
                "MAIL_PASSWORD" => $request->get('sendgridApiKey'),
            ];

            if (count($values) > 0) {
                foreach ($values as $envKey => $envValue) {

                    $str .= "\n"; // In case the searched variable is in the last line without \n
                    $keyPosition = strpos($str, "{$envKey}=");
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                    $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                    // If key does not exist, add it
                    if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                        $str .= "{$envKey}={$envValue}\n";
                    } else {
                        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                    }

                }
            }

            $str = substr($str, 0, -1);
            if (!file_put_contents($envFile, $str)) {
                return false;
            }
            $this->clearCache();

            return redirect()->back()->with('success', 'Credential store successfully!');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something went wrong in input!');

        }

    }
    public function clearCache()
    {
        // \Artisan::call('config:cache');
        \Artisan::call('view:clear');
        \Artisan::call('route:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
    }
    public function create_group(Request $request)
    {
        try {
            $group = new Group;
            $group->group_name = $request->group_name;
            $group->group_type = 'Static';
            $group->group_tag = $request->createGroupToken;
            // $group->group_emails = $request->group_email;
            $group->group_last_modified = Carbon::now();
            $group->group_created_at = Carbon::now();
            $group->group_members = '0';

            $group_recordids = Group::select("group_recordid")->distinct()->get();
            $group_recordid_list = array();
            foreach ($group_recordids as $key => $value) {
                $group_recordid = $value->group_recordid;
                array_push($group_recordid_list, $group_recordid);
            }
            $group_recordid_list = array_unique($group_recordid_list);

            $new_recordid = Group::max('group_recordid') + 1;
            if (in_array($new_recordid, $group_recordid_list)) {
                $new_recordid = Group::max('group_recordid') + 1;
            }
            $group->group_recordid = $new_recordid;

            $group->save();

            return response()->json([
                'message' => 'Group created successfully!',
                'data' => $new_recordid,
                'success' => true,
            ], 200);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false,
            ], 500);

        }

    }
    public function getGroupTag(Request $request)
    {
        try {
            $groupRecordId = $request->get('groupRecordId');
            $group = Group::where('group_recordid', $groupRecordId)->first();
            $group_tag = $group ? $group->group_tag : '';

            return response()->json([
                'data' => $group_tag,
                'success' => true,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false,
            ], 500);

        }
    }
    public function createMessage()
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

            $contactDetail = Contact::orderBy('id', 'desc')->where('contact_cell_phones', '!=', '')->pluck('contact_first_name', 'contact_cell_phones');
            $gorupDetail = Group::pluck('group_name', 'group_recordid');

            return view('backEnd.messages.createMessage', compact('home', 'taxonomies', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'gorupDetail', 'contactDetail'));

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMesage());
        }
    }
    public function saveContactInfo(Request $request)
    {
        try {
            $id = $request->get('id');
            $type = $request->get('type');
            $email = $request->get('email');
            $phone = $request->get('phone');
            $officePhone = $request->get('officePhone');
            DB::beginTransaction();
            if ($type == 1) {
                if ($email != '') {
                    Contact::whereId($id)->update([
                        'contact_email' => $email,
                    ]);
                    DB::commit();

                    return response()->json([
                        'message' => 'Email added successfully!',
                        'success' => true,
                    ], 200);
                }
                return response()->json([
                    'message' => 'Email is required!',
                    'success' => false,
                ], 500);
            } else {
                $contact = Contact::whereId($id)->first();
                if ($type == 2) {
                    Phone::where('phone_recordid', $contact->contact_cell_phones)->update([
                        'phone_number' => $phone,
                        'phone_type' => 'cell phone',
                    ]);
                    DB::commit();
                    return response()->json([
                        'message' => 'Phone added successfully!',
                        'success' => true,
                    ], 200);

                } else {
                    Phone::where('phone_recordid', $contact->contact_office_phones)->update([
                        'phone_number' => $phone,
                        'phone_type' => 'office phone',
                    ]);
                    DB::commit();
                    return response()->json([
                        'message' => 'Office phone added successfully!',
                        'success' => true,
                    ], 200);

                }
            }

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false,
            ], 500);
        }
    }
}
