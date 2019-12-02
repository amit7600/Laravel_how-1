<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Functions\Airtable;
use App\Airtables;
use App\Group;
use App\Location;
use App\Contact;
use App\Map;
use App\Source_data;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function groups()
    {
        $groups = Group::orderBy('group_recordid')->get();        
        $type_list = ['Static', 'Dynamic', 'Previously Messaged']; 
        $map = Map::find(1);       

        return view('frontEnd.groups', compact('groups', 'type_list', 'map'));        
    }

    public function tagging(Request $request, $id) {
        $group = Group::find($id); 
        $group->group_tag = $request->tokenfield;
        $group->save();
        return redirect('group/'.$id);
    }


    public function group($id) {
        $group = Group::where('group_recordid', '=', $id)->first(); 
        $group_type = $group->group_type;        
        
        if ($group_type == 'Static') {
            $contacts = Contact::orderBy('contact_recordid')
                ->where('contact_group', 'LIKE', '%'.$group->group_recordid.'%')
                ->join('organizations', 'organizations.organization_recordid', '=', 'contacts.contact_organizations')
                ->join('locations', 'locations.location_recordid', '=', 'organizations.organization_locations')
                ->join('address', 'address.address_recordid', '=', 'locations.location_address')
                ->get();

            $group_members_list = Contact::orderBy('contact_recordid')->where('contacts.contact_group', '=', $id)->select('contact_recordid')->get();

            $locations = [];
            foreach ($group_members_list as $key => $value) {            
                $group_contact_recordid = $value->contact_recordid;            
                $location_info = Location::with('services', 'address', 'phones')->where('location_contact', '=', $group_contact_recordid)->get();
                array_push($locations, $location_info);
            }   
        }
        if ($group_type == 'Dynamic') {

            $group_filters = $group->group_filters;
            $filters = json_decode($group_filters);

            $religion_filter = $filters->religion_filter;
            $faith_tradition_filter = $filters->faith_tradition_filter;            
            $denomination_filter = $filters->denomination_filter;
            $judicatory_body_filter = $filters->judicatory_body_filter;       
            $email_filter = $filters->email_filter;
            $phone_filter = $filters->phone_filter;
            $contact_type_filter = $filters->contact_type_filter;
            $contact_languages_filter = $filters->contact_languages_filter;
            $contact_address_filter = $filters->contact_address_filter;
            $contact_borough_filter = $filters->contact_borough_filter;
            $contact_zipcode_filter = $filters->contact_zipcode_filter;            

            $contacts = Contact::orderBy('contact_recordid')
                ->where('contacts.contact_type', '=', $contact_type_filter)
                ->where('contacts.contact_languages_spoken', '=', $contact_languages_filter)
                ->join('organizations', 'organizations.organization_recordid', '=', 'contacts.contact_organizations')
                ->join('locations', 'locations.location_recordid', '=', 'organizations.organization_locations')
                ->join('address', 'address.address_recordid', '=', 'locations.location_address');
            
            if ($religion_filter != NULL) {
                $contacts = $contacts->where('organizations.organization_religion', '=', $religion_filter);
            }
            if ($faith_tradition_filter != NULL) {
                $contacts = $contacts->where('organizations.organization_faith_tradition', '=', $faith_tradition_filter);
            }
            if ($denomination_filter != NULL) {
                $contacts = $contacts->where('organizations.organization_denomination', '=', $denomination_filter);
            }
            if ($judicatory_body_filter != NULL) {
                $contacts = $contacts->where('organizations.organization_judicatory_body', '=', $judicatory_body_filter);
            }
            if ($contact_address_filter != NULL) {
                $contacts = $contacts->where('address.address', '=', '%'.$contact_address_filter.'%');
            }
            if ($contact_zipcode_filter != NULL) {
                $contacts = $contacts->where('address.address', '=', '%'.$contact_zipcode_filter.'%');
            }
            if ($contact_borough_filter != NULL) {
                $contacts = $contacts->where('address.address', '=', '%'.$contact_borough_filter.'%');
            }
            if ($email_filter == 'No Email') {
                $contacts = $contacts->whereNull('contacts.contact_personal_email');
            }
            if ($email_filter == 'Has Email') {
                $contacts = $contacts->whereNotNull('contacts.contact_personal_email');
            }
            if ($phone_filter == 'No Phone') {
                $contacts = $contacts->whereNull('contacts.contact_cell_phones');
            }
            if ($phone_filter == 'Has Phone') {
                $contacts = $contacts->whereNotNull('contacts.contact_cell_phones');
            }

            $contacts = $contacts->get();

            $locations = [];
            foreach ($contacts as $key => $value) {            
                $group_contact_recordid = $value->contact_recordid;            
                $location_info = Location::with('services', 'address', 'phones')->where('location_contact', '=', $group_contact_recordid)->get();
                array_push($locations, $location_info);
            }

        }

        $map = Map::find(1);
        $group_members_info = Group::where('group_recordid', '=', $id)->select('group_members')->first();
        $group_date_created_info = Group::where('group_recordid', '=', $id)->select('group_created_at')->first();
        $group_date_created = $group_date_created_info['group_created_at'];
        
        return view('frontEnd.group', compact('group', 'map', 'locations', 'contacts', 'group_date_created'));  
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $map = Map::find(1);
        return view('frontEnd.group-create', compact('map'));
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
        $group = Group::where('group_recordid', '=', $id)->first(); 
        $group_type_list = ['Dynamic', 'Static'];
        $map = Map::find(1); 
        return view('frontEnd.group-edit', compact('map', 'group', 'group_type_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function add_new_group(Request $request)
    {
        $group = new Group;
        $group->group_name = $request->group_name;        
        // $group->group_type = $request->group_type;
        // $group->group_emails = $request->group_email;
        $group->group_last_modified = date("Y-m-d h:i:sa");
        $group->group_created_at = date("Y-m-d h:i:sa");
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
        return redirect('groups');
    }

    public function update(Request $request, $id)
    {
        $group = Group::find($id);
        $group->group_name = $request->group_name;        
        $group->group_type = $request->group_type;
        $group->group_emails = $request->group_email;
        $group->group_last_modified = date("Y-m-d h:i:sa");
        $group->save();
        return redirect('group/'.$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }

    public function group_remove_members(Request $request){
        $group_recordid = $request->input('group_recordid'); 
        $checked_terms = $request->input('checked_terms'); 
        $checked_terms_list = explode(",", $checked_terms);
        foreach ($checked_terms_list as $key => $value) {
            $checked_contact = Contact::where('contact_recordid', '=', $value)->first();
            $checked_contact->contact_group = str_replace(', '.$group_recordid, "", $checked_contact->contact_group);
            $checked_contact->contact_group = str_replace($group_recordid.', ', "", $checked_contact->contact_group);
            $checked_contact->contact_group = str_replace($group_recordid, "", $checked_contact->contact_group);
            $checked_contact->save();
        }

        $group = Group::where('group_recordid', '=', $group_recordid)->first();
        $group_members_count = Contact::where('contact_group', 'LIKE', '%'.$group_recordid.'%')->count();
        $group->group_members = $group_members_count;
        $group->save();

        return redirect('group/'.$group_recordid);
    }

    public function delete_group(Request $request){
        $group_recordid = $request->input('group_recordid'); 
         
        $group = Group::where('group_recordid', '=', $group_recordid)->first();
        $group->delete();

        return redirect('groups');
    }
}
