<?php

namespace App\Http\Controllers;

use Session;
use Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Address;
use App\Organization;
use App\Contact;
use App\Comment;
use App\Organizationdetail;
use App\Organizationcontact;
use App\Location;
use App\Layout;
use App\Map;
use App\CSV;
use App\Airtables;
use App\Source_data;
use App\Services\Stringtoint;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use PDF;

class OrganizationController extends Controller
{

    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user= Auth::user();

            return $next($request);
        });
    }

    public function airtable()
    {

        Organization::truncate();
        Organizationdetail::truncate();
        Organizationcontact::truncate();

        $airtable = new Airtable(array(
            'api_key'   => env('AIRTABLE_API_KEY'),
            'base'      => env('AIRTABLE_BASE_URL'),
        ));

        $request = $airtable->getContent( 'organizations' );

        do {


            $response = $request->getResponse();

            $airtable_response = json_decode( $response, TRUE );

            foreach ( $airtable_response['records'] as $record ) {

                $organization = new Organization();
                $strtointclass = new Stringtoint();
                $organization->organization_recordid= $strtointclass->string_to_int($record[ 'id' ]);

                $organization->organization_name = isset($record['fields']['name'])?$record['fields']['name']:null;

                $organization->organization_id = isset($record['fields']['id'])?$record['fields']['id']:null;

                $organization->organization_alt_id = isset($record['fields']['alt id-z'])?$record['fields']['alt id-z']:null;

                $organization->organization_religion = isset($record['fields']['Religion-z'])?$record['fields']['Religion-z']:null;

                $organization->organization_faith_tradition = isset($record['fields']['Faith Tradition-z'])?$record['fields']['Faith Tradition-z']:null;

                $organization->organization_denomination = isset($record['fields']['Denomination-z'])?$record['fields']['Denomination-z']:null;

                $organization->organization_judicatory_body = isset($record['fields']['Judicatory Body-z'])?$record['fields']['Judicatory Body-z']:null;

                $organization->organization_type = isset($record['fields']['Organization Type-z'])?$record['fields']['Organization Type-z']:null;

                $organization->organization_url = isset($record['fields']['url'])?$record['fields']['url']:null;

                $organization->organization_facebook = isset($record['fields']['Facebook-z'])?$record['fields']['Facebook-z']:null;

                $organization->organization_c_board = isset($record['fields']['C Board-z'])? implode(",", $record['fields']['C Board-z']):null;

                $organization->organization_internet_access = isset($record['fields']['Internet Access-z'])?$record['fields']['Internet Access-z']:null;

                $organization->organization_description = isset($record['fields']['description'])?$record['fields']['description']:null;

                $organization->organization_description =  mb_convert_encoding($organization->organization_description, "HTML-ENTITIES", "UTF-8");

                if(isset($record['fields']['locations'])){
                    $i = 0;
                    foreach ($record['fields']['locations']  as  $value) {

                        $organizationlocation=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $organization->organization_locations = $organization->organization_locations. ','. $organizationlocation;
                        else
                            $organization->organization_locations = $organizationlocation;
                        $i ++;
                    }
                }

                $organization->organization_borough = isset($record['fields']['borough-o'])?$record['fields']['borough-o']:null;

                $organization->organization_zipcode = isset($record['fields']['zipcode-o'])?$record['fields']['zipcode-o']:null;

                if(isset($record['fields']['details'])){
                    $i = 0;
                    foreach ($record['fields']['details']  as  $value) {
                        $organization_detail = new Organizationdetail();
                        $organization_detail->organization_recordid=$organization->organization_recordid;
                        $organization_detail->detail_recordid=$strtointclass->string_to_int($value);
                        $organization_detail->save();
                        $organizationdetail=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $organization->organization_details = $organization->organization_details. ','. $organizationdetail;
                        else
                            $organization->organization_details = $organizationdetail;
                        $i ++;
                    }
                }

                if(isset($record['fields']['contact'])){
                    $i = 0;
                    foreach ($record['fields']['contact']  as  $value) {
                        $organization_contact = new Organizationcontact();
                        $organization_contact->organization_recordid=$organization->organization_recordid;
                        $organization_contact->contact_recordid=$strtointclass->string_to_int($value);
                        $organization_contact->save();
                        $organizationcontact=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $organization->organization_contact = $organization->organization_contact. ','. $organizationcontact;
                        else
                            $organization->organization_contact = $organizationcontact;
                        $i ++;
                    }
                }    

                $organization ->save();

            }
            
        }
        while( $request = $response->next() );

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Organizations')->first();
        $airtable->records = Organization::count();
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
        $organizations = Organization::orderBy('organization_recordid')->paginate(20);
        $source_data = Source_data::find(1);

        return view('backEnd.tables.tb_organization', compact('organizations', 'source_data'));
    }

    public function get_all_organizations(Request $request) 
    {
        $start = $request->start;
        $length = $request->length;
        $search_term = $request->search_term;

        $filter_religion = $request->filter_religion;       
        $filter_faith_tradition = $request->filter_faith_tradition;
        $filter_denomination = $request->filter_denomination;
        $filter_judicatory_body = $request->filter_judicatory_body;
        $filter_type = $request->filter_type;       
        $filter_tag = $request->filter_tag;
        $filter_map = $request->filter_map;

        $organizations = Organization::orderBy('organization_recordid', 'DESC');
        if ($search_term) {
            $organizations =  $organizations
                ->where('organization_name', 'LIKE', '%' . $search_term . '%')
                ->orWhere('organization_religion', 'LIKE', '%' . $search_term . '%')
                ->orWhere('organization_faith_tradition', 'LIKE', '%' . $search_term . '%')
                ->orWhere('organization_denomination', 'LIKE', '%' . $search_term . '%')
                ->orWhere('organization_judicatory_body', 'LIKE', '%' . $search_term . '%')
                ->orWhere('organization_type', 'LIKE', '%' . $search_term . '%')
                ->orWhere('organization_url', 'LIKE', '%' . $search_term . '%')
                ->orWhere('organization_description', 'LIKE', '%' . $search_term . '%')
                ->orWhere('organization_tag', 'LIKE', '%' . $search_term . '%');
        }

        $filter_religion_list = [];
        $filter_faith_tradition_list = [];
        $filter_denomination_list = [];
        $filter_judicatory_body_list = [];
        $filter_type_list = [];        
        $filter_map_list = [];

        if ($filter_religion) {
            $filter_religion_list = explode('|', $filter_religion);            
        }
        if ($filter_faith_tradition) {
            $filter_faith_tradition_list = explode('|', $filter_faith_tradition);            
        }
        if ($filter_denomination) {
            $filter_denomination_list = explode('|', $filter_denomination);            
        }
       
        if ($filter_judicatory_body) {
            $filter_judicatory_body_list = explode('|', $filter_judicatory_body);            
        }
        if ($filter_type) {
            $filter_type_list = explode('|', $filter_type);            
        }
        if ($filter_map) {
            $filter_map_list = json_decode($filter_map);
        }

        $filtered_location_recordid_list = [];
        if ($filter_map_list) {
            $lat = round($filter_map_list[0]->lat, 7);
            $lng = round($filter_map_list[0]->lng, 7);
            $query = Location::where(function ($q) use ($lat, $lng) {
                $q->where('location_latitude', $lat)
                    ->where('location_longitude', $lng);
            });
            foreach($filter_map_list as $key => $filter_map_value) {
                if ($key == 0) continue;
                $lat = round($filter_map_value->lat, 7);
                $lng = round($filter_map_value->lng, 7);
                $query = $query->orWhere(function ($q) use ($lat, $lng) {
                    $q->where('location_latitude', $lat)
                        ->where('location_longitude', $lng);
                });
            }
            $filtered_location_recordid_list = $query->pluck('location_recordid')->toArray();
            
        }

        if ($filter_religion_list) {
            $organizations = $organizations->whereIn('organization_religion', $filter_religion_list);
        }
        if ($filter_faith_tradition_list) {
            $organizations = $organizations->whereIn('organization_faith_tradition', $filter_faith_tradition_list);
        }
        if ($filter_denomination_list) {
            $organizations = $organizations->whereIn('organization_denomination', $filter_denomination_list);
        }
        if ($filter_judicatory_body_list) {
            $organizations = $organizations->whereIn('organization_judicatory_body', $filter_judicatory_body_list);
        }
        if ($filter_type_list) {
            $organizations = $organizations->whereIn('organization_type', $filter_type_list);
        }
        if ($filter_tag) {
            $organizations = $organizations->where('organization_tag', 'LIKE', '%' . $filter_tag . '%');
        }
        if ($filtered_location_recordid_list) {
            $organizations = $organizations->whereIn('organization_locations', $filtered_location_recordid_list);
        }

        if ($filter_religion_list || $filter_faith_tradition_list || $filter_denomination_list || $filter_judicatory_body_list || $filter_type_list || $filter_type_list || $filtered_location_recordid_list) {

            $filtered_locations_list = new Collection();
            $filtered_all_organizations = $organizations->get();            
            foreach ($filtered_all_organizations as $key => $value) {
                $filtered_locations = $value->location;
                $filtered_locations_list = $filtered_locations_list->merge($filtered_locations);
            }
        }
        else {
            $filtered_locations_list = Location::with('services', 'address', 'phones')->distinct()->get();
        }

        $filtered_count = $organizations->count();
        
        $organizations = $organizations->offset($start)->limit($length)->get();
        $total_count = Organization::count();
        $result = [];
        $organization_info = [];
        foreach ($organizations as $organization) {
            $organization_info[0] = '';
            $organization_info[1] = '';
            $organization_info[2] = $organization->organization_recordid;
            $organization_info[3] = str_limit($organization->organization_name, 50, '...');
            $organization_info[4] = $organization->organization_religion;
            $organization_info[5] = $organization->organization_faith_tradition;
            $organization_info[6] = str_limit($organization->organization_denomination, 30, '...');
            $organization_info[7] = $organization->organization_judicatory_body;
            $organization_info[8] = $organization->organization_type;
            $organization_info[9] = $organization->organization_url;
            $organization_info[10] = $organization->organization_facebook;
            $organization_info[11] = $organization->organization_internet_access;
            $organization_info[12] = $organization->organization_description;
            $organization_info[13] = $organization->organization_tag;

            array_push($result, $organization_info);
        }
        return response()->json(array('data'=>$result, 'recordsTotal'=>$total_count, 'recordsFiltered'=>$filtered_count, 'filtered_locations_list'=>$filtered_locations_list));
    }

    public function group_operation(Request $request) {
      
        switch ($request->input('btn_submit')) {
            case 'download_csv':

                $filter_religion = $request->input('religion_list');
                $filter_faith_tradition = $request->input('faith_tradition_list');
                $filter_denomination = $request->input('denomination_list');
                $filter_judicatory_body = $request->input('judicatory_body_list');
                $filter_type = $request->input('type_list');

                $organizations = Organization::orderBy('organization_recordid', 'DESC');

                $filter_religion_list = [];
                $filter_faith_tradition_list = [];
                $filter_denomination_list = [];
                $filter_judicatory_body_list = [];
                $filter_type_list = [];

                if ($filter_religion) {
                    $filter_religion_list = explode(',', $filter_religion);            
                }
                
                if ($filter_faith_tradition) {
                    $filter_faith_tradition_list = explode(',', $filter_faith_tradition);            
                }
                if ($filter_denomination) {
                    $filter_denomination_list = explode(',', $filter_denomination);            
                }
               
                if ($filter_judicatory_body) {
                    $filter_judicatory_body_list = explode(',', $filter_judicatory_body);            
                }
                if ($filter_type) {
                    $filter_type_list = explode(',', $filter_type);            
                }

                if ($filter_religion_list) {
                    $organizations = $organizations->whereIn('organization_religion', $filter_religion_list);
                }
                if ($filter_faith_tradition_list) {
                    $organizations = $organizations->whereIn('organization_faith_tradition', $filter_faith_tradition_list);
                }
                if ($filter_denomination_list) {
                    $organizations = $organizations->whereIn('organization_denomination', $filter_denomination_list);
                }
                if ($filter_judicatory_body_list) {
                    $organizations = $organizations->whereIn('organization_judicatory_body', $filter_judicatory_body_list);
                }
                if ($filter_type_list) {
                    $organizations = $organizations->whereIn('organization_type', $filter_type_list);
                }

                $organizations = $organizations->get();

                $csvExporter = new \Laracsv\Export();

                $csv = CSV::find(1);
                $layout = Layout::find(1);                
                $source = $layout->footer_csv;
                $csv->description = $source;
                $csv->save();

                $csv = CSV::all();

                return $csvExporter->build($organizations, [  
                    'organization_name'=>'Organization Name', 'organization_faith_tradition'=>'Faith Tradition',
                    'organization_denomination'=>'Organization Denomination', 'organization_judicatory_body'=>'Judicatory Body',
                    'organization_type'=>'Organization Type'])
                    ->build($csv, ['description'=>''])
                    ->download();
                
                break;

            case null:

                $filter_religion = $request->input('religion_list');
                $filter_faith_tradition = $request->input('faith_tradition_list');
                $filter_denomination = $request->input('denomination_list');
                $filter_judicatory_body = $request->input('judicatory_body_list');
                $filter_type = $request->input('type_list');
                $organization_map_image = $request->input('organization_map_image');

                $organizations = Organization::orderBy('organization_recordid', 'DESC');

                $filter_religion_list = [];
                $filter_faith_tradition_list = [];
                $filter_denomination_list = [];
                $filter_judicatory_body_list = [];
                $filter_type_list = [];

                if ($filter_religion) {
                    $filter_religion_list = explode(',', $filter_religion);            
                }
                if ($filter_faith_tradition) {
                    $filter_faith_tradition_list = explode(',', $filter_faith_tradition);            
                }
                if ($filter_denomination) {
                    $filter_denomination_list = explode(',', $filter_denomination);            
                }
            
                if ($filter_judicatory_body) {
                    $filter_judicatory_body_list = explode(',', $filter_judicatory_body);            
                }
                if ($filter_type) {
                    $filter_type_list = explode(',', $filter_type);            
                }

                if ($filter_religion_list) {
                    $organizations = $organizations->whereIn('organization_religion', $filter_religion_list);
                }
                if ($filter_faith_tradition_list) {
                    $organizations = $organizations->whereIn('organization_faith_tradition', $filter_faith_tradition_list);
                }
                if ($filter_denomination_list) {
                    $organizations = $organizations->whereIn('organization_denomination', $filter_denomination_list);
                }
                if ($filter_judicatory_body_list) {
                    $organizations = $organizations->whereIn('organization_judicatory_body', $filter_judicatory_body_list);
                }
                if ($filter_type_list) {
                    $organizations = $organizations->whereIn('organization_type', $filter_type_list);
                }

                $organizations = $organizations->get(); 
                
                $layout = Layout::find(1);
                set_time_limit(0);
                $pdf = PDF::loadView('frontEnd.organizations_download', compact('organizations', 'layout', 'organization_map_image'));
                return $pdf->download('organizations.pdf');

                break;
        }
    }

    public function organizations()
    {
   
        // $organizations = Organization::orderBy('organization_recordid', 'DESC')
        // ->paginate(200);

        $address_cities = Address::select("address_city")->distinct()->get();

        $organization_religions = Organization::select("organization_religion")->distinct()->get();
        $organization_faith_traditions = Organization::select("organization_faith_tradition")->distinct()->get();
        $organization_denominations = Organization::select("organization_denomination")->distinct()->get();
        $organization_judicatory_bodys = Organization::select("organization_judicatory_body")->distinct()->get();
        $organization_types = Organization::select("organization_type")->distinct()->get();
        $organization_locations = Organization::select("organization_locations")->distinct()->get();
        $organization_tags = Organization::select("organization_tag")->distinct()->get();
        $locations = Location::with('services', 'address', 'phones')->distinct()->get();

        $tag_list = [];
        foreach ($organization_tags as $key => $value) {
            $tags = explode(", " , trim($value->organization_tag));
            $tag_list = array_merge($tag_list, $tags);
        }
        $tag_list = array_unique($tag_list);

        $address_city_list = [];
        foreach ($address_cities as $key => $value) {
            $cities = explode(", " , trim($value->address_city));
            $address_city_list = array_merge($address_city_list, $cities);
        }
        $address_city_list = array_unique($address_city_list);

        $type_list = [];
        foreach ($organization_types as $key => $value) {
            $types = explode(", " , trim($value->organization_type));
            $type_list = array_merge($type_list, $types);
        }
        $type_list = array_unique($type_list);

        $religion_list = [];
        foreach ($organization_religions as $key => $value) {
            $religions = explode(", " , trim($value->organization_religion));
            $religion_list = array_merge($religion_list, $religions);
        }
        $religion_list = array_unique($religion_list);

        $faith_tradition_list = [];
        foreach ($organization_faith_traditions as $key => $value) {
            $faith_traditions = explode(", " , trim($value->organization_faith_tradition));
            $faith_tradition_list = array_merge($faith_tradition_list, $faith_traditions);
        }
        $faith_tradition_list = array_unique($faith_tradition_list);

        $denomination_list = [];
        foreach ($organization_denominations as $key => $value) {
            $denominations = explode(", " , trim($value->organization_denomination));
            $denomination_list = array_merge($denomination_list, $denominations);
        }
        $denomination_list = array_unique($denomination_list);

        $judicatory_body_list = [];
        foreach ($organization_judicatory_bodys as $key => $value) {
            $organization_judicatory_body_value = $value->organization_judicatory_body;
            if (strpos($organization_judicatory_body_value, '(') !== false) {
                $judicatory_bodys_value = explode ("(", $organization_judicatory_body_value)[0]; 
            }
            else {
                $judicatory_bodys_value = $organization_judicatory_body_value;
            }
            $judicatory_bodys = explode(", " , trim($judicatory_bodys_value));
            $judicatory_body_list = array_merge($judicatory_body_list, $judicatory_bodys);
        }
        $judicatory_body_list = array_unique($judicatory_body_list);

        $organization_location_list = [];
        foreach ($organization_locations as $key => $value) {
            $organization_locations_value = explode("," , trim($value->organization_locations));
            $organization_location_list = array_merge($organization_location_list, $organization_locations_value);
        }
        $organization_location_list = array_unique($organization_location_list);
       
        $map = Map::find(1);
        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        $checked_insurances = [];
        $checked_ages = [];
        $checked_languages = [];
        $checked_settings = [];
        $checked_culturals = [];
        $checked_transportations = [];
        $checked_hours= [];

        return view('frontEnd.organizations', compact('address_city_list', 'organization_religions', 'organization_faith_traditions', 'organization_denominations', 'organization_judicatory_bodys', 'organization_types', 'organization_locations', 'locations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 
        'type_list', 'tag_list', 'religion_list', 'faith_tradition_list', 'denomination_list', 'judicatory_body_list', 'organization_location_list'));
    }

    public function organization($id)
    {
        $organization = Organization::where('organization_recordid', '=', $id)->first();
        $contacts = Contact::orderBy('contact_recordid')->where('contacts.contact_organizations', '=', $id)->get();
        $locations = Location::with('services', 'address', 'phones')->where('location_organization', '=', $id)->get();
        $map = Map::find(1);
        $comment_list = Comment::where('comments_organization', '=', $id)->get();

        return view('frontEnd.organization', compact('organization', 'contacts', 'locations', 'map', 'parent_taxonomy', 'comment_list'));
    }

    public function download($id)
    {
        $organization = Organization::where('organization_recordid', '=', $id)->first();
        $organization_name = $organization->organization_name;

        $layout = Layout::find(1);

        $pdf = PDF::loadView('frontEnd.organization_download', compact('organization', 'layout'));

        return $pdf->download($organization_name.'.pdf');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $map = Map::find(1);
        $organization_types = Organization::select("organization_type")->distinct()->get();
        $organization_religions = Organization::select("organization_religion")->distinct()->get();
        $organization_faith_traditions = Organization::select("organization_faith_tradition")->distinct()->get();
        $organization_denominations = Organization::select("organization_denomination")->distinct()->get();
        $organization_judicatory_bodies = Organization::select("organization_judicatory_body")->distinct()->get();

        $organization_type_list = [];
        foreach ($organization_types as $key => $value) {
            $org_types = explode(", " , trim($value->organization_type));
            $organization_type_list = array_merge($organization_type_list, $org_types);
        }        
        $organization_type_list = array_unique($organization_type_list);

        $organization_religion_list = [];
        foreach ($organization_religions as $key => $value) {
            $org_religions = explode(", " , trim($value->organization_religion));
            $organization_religion_list = array_merge($organization_religion_list, $org_religions);
        }        
        $organization_religion_list = array_unique($organization_religion_list);

        $organization_faith_tradition_list = [];
        foreach ($organization_faith_traditions as $key => $value) {
            $org_faith_traditions = explode(", " , trim($value->organization_faith_tradition));
            $organization_faith_tradition_list = array_merge($organization_faith_tradition_list, $org_faith_traditions);
        }        
        $organization_faith_tradition_list = array_unique($organization_faith_tradition_list);

        $organization_denomination_list = [];
        foreach ($organization_denominations as $key => $value) {
            $org_denominations = explode(", " , trim($value->organization_denomination));
            $organization_denomination_list = array_merge($organization_denomination_list, $org_denominations);
        }        
        $organization_denomination_list = array_unique($organization_denomination_list);

        $organization_judicatory_body_list = [];
        foreach ($organization_judicatory_bodies as $key => $value) {
            $org_judicatory_bodies = explode(", " , trim($value->organization_judicatory_body));
            $organization_judicatory_body_list = array_merge($organization_judicatory_body_list, $org_judicatory_bodies);
        }        
        $organization_judicatory_body_list = array_unique($organization_judicatory_body_list);

        return view('frontEnd.organization-create', compact('map', 'organization_type_list', 'organization_religion_list', 
        'organization_religion_list', 'organization_faith_tradition_list', 'organization_denomination_list', 
        'organization_judicatory_body_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $organization= Organization::find($id);
        return response()->json($organization);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function tagging(Request $request, $id) {
        $organization = Organization::find($id); 
        $organization->organization_tag = $request->tokenfield;
        $organization->save();
        return redirect('organization/'.$id);
    }


    public function edit($id)
    {
        $map = Map::find(1);
        $organization = Organization::where('organization_recordid', '=', $id)->first(); 
        $organization_types = Organization::select("organization_type")->distinct()->get();
        $organization_religions = Organization::select("organization_religion")->distinct()->get();
        $organization_faith_traditions = Organization::select("organization_faith_tradition")->distinct()->get();
        $organization_denominations = Organization::select("organization_denomination")->distinct()->get();
        $organization_judicatory_bodies = Organization::select("organization_judicatory_body")->distinct()->get();

        $organization_type_list = [];
        foreach ($organization_types as $key => $value) {
            $org_types = explode(", " , trim($value->organization_type));
            $organization_type_list = array_merge($organization_type_list, $org_types);
        }        
        $organization_type_list = array_unique($organization_type_list);

        $organization_religion_list = [];
        foreach ($organization_religions as $key => $value) {
            $org_religions = explode(", " , trim($value->organization_religion));
            $organization_religion_list = array_merge($organization_religion_list, $org_religions);
        }        
        $organization_religion_list = array_unique($organization_religion_list);

        $organization_faith_tradition_list = [];
        foreach ($organization_faith_traditions as $key => $value) {
            $org_faith_traditions = explode(", " , trim($value->organization_faith_tradition));
            $organization_faith_tradition_list = array_merge($organization_faith_tradition_list, $org_faith_traditions);
        }        
        $organization_faith_tradition_list = array_unique($organization_faith_tradition_list);

        $organization_denomination_list = [];
        foreach ($organization_denominations as $key => $value) {
            $org_denominations = explode(", " , trim($value->organization_denomination));
            $organization_denomination_list = array_merge($organization_denomination_list, $org_denominations);
        }        
        $organization_denomination_list = array_unique($organization_denomination_list);

        $organization_judicatory_body_list = [];
        foreach ($organization_judicatory_bodies as $key => $value) {
            $org_judicatory_bodies = explode(", " , trim($value->organization_judicatory_body));
            $organization_judicatory_body_list = array_merge($organization_judicatory_body_list, $org_judicatory_bodies);
        }        
        $organization_judicatory_body_list = array_unique($organization_judicatory_body_list);

        $organization_location_info = Organization::where('organization_recordid', '=', $id)->select('organization_locations')->first();
        $organization_location_id = $organization_location_info['organization_locations'];  
        $location_address = Location::where('location_recordid', '=', $organization_location_id)->select('location_address')->first();   
        $location_address_id = $location_address["location_address"];
        $address_info = Address::where('address_recordid', '=', $location_address_id)->select('address')->first();
        $organization_location_address = $address_info['address']; 

        return view('frontEnd.organization-edit', compact('organization', 'map', 'organization_type_list', 
            'organization_religion_list', 'organization_faith_tradition_list', 'organization_denomination_list', 
            'organization_judicatory_body_list', 'organization_location_address'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function add_comment(Request $request, $id) {

        $contacts = Contact::orderBy('contact_recordid')->where('contacts.contact_organizations', '=', $id)->get();
        $locations = Location::with('services', 'address', 'phones')->where('location_organization', '=', $id)->get();
        $map = Map::find(1);

        $organization = Organization::find($id);
        $comment_content = $request->reply_content;
        $user = Sentinel::getUser();
        $date_time = date("Y-m-d h:i:sa");
        $comment = new Comment();

        $comment_recordids = Comment::select("comments_recordid")->distinct()->get();                 
        $comment_recordid_list = array();
        foreach ($comment_recordids as $key => $value) {
            $comment_recordid = $value->comments_recordid;
            array_push($comment_recordid_list, $comment_recordid);
        }
        $comment_recordid_list = array_unique($comment_recordid_list);
        $new_recordid = Comment::max('comments_recordid') + 1;            
        if (in_array($new_recordid, $comment_recordid_list)) {
            $new_recordid = Comment::max('comments_recordid') + 1;
        }     

        $comment->comments_recordid = $new_recordid;
        $comment->comments_content = $comment_content;
        $comment->comments_user = $user->id;
        $comment->comments_user_firstname = $user->first_name;
        $comment->comments_user_lastname = $user->last_name;
        $comment->comments_organization = $id;
        $comment->comments_datetime = $date_time;
        $comment->save();

        $comment_list = Comment::where('comments_organization', '=', $id)->get();

        // return view('frontEnd.organization', compact('organization', 'contacts', 'locations', 'map', 'user', 'comment_list'));
        return redirect('organization/'.$id);
        
    }
    public function update(Request $request, $id)
    {
        $organization = Organization::find($id);
        $organization->organization_name = $request->organization_organization_name;
        $organization->organization_id = $request->organization_organization_id;
        $organization->organization_religion = $request->organization_organization_religion;
        $organization->organization_alt_id = $request->organization_organization_id;
        $organization->organization_faith_tradition = $request->organization_organization_faith_tradition;
        $organization->organization_denomination = $request->organization_organization_denomination;
        $organization->organization_judicatory_body = $request->organization_organization_judicatory_body;
        $organization->organization_type = $request->organization_organization_type;
        $organization->organization_url = $request->organization_organization_website;
        $organization->organization_facebook = $request->organization_organization_facebook;
        $organization->organization_c_board = $request->organization_organization_c_board; 
        if ($request->organization_organization_internete_access == 'on') {
            $organization->organization_internet_access = "yes";
        }
        else {
            $organization->organization_internet_access = "no";
        }

        $organization_facility_address = $request->organization_organization_facility_address;        
        $location_recordids = Location::select("location_recordid")->distinct()->get();                 
        $location_recordid_list = array();
        foreach ($location_recordids as $key => $value) {
            $location_recordid = $value->location_recordid;
            array_push($location_recordid_list, $location_recordid);
        }
        $location_recordid_list = array_unique($location_recordid_list); 

        $address_recordids = Address::select("address_recordid")->distinct()->get();                 
        $address_recordid_list = array();
        foreach ($address_recordids as $key => $value) {
            $address_recordid = $value->location_recordid;
            array_push($address_recordid_list, $address_recordid);
        }
        $location_recordid_list = array_unique($location_recordid_list); 

        $address = Address::where('address', '=', $organization_facility_address)->first();
        if($address != Null) {
            $address_id = $address["address_recordid"];
            $location_address_info = $address_id;
        }
        else {
            $address = new Address;
            $new_recordid = strval(rand(5339708216194320, 9999999999999990));            
            if (in_array($new_recordid, $address_recordid_list)) {
                $new_recordid = strval(rand(5339708216194320, 9999999999999990));
            }            
            $address->address_recordid = $new_recordid;
            $address->address = $organization_facility_address; 
            $address_reference_list = explode (",", $organization_facility_address);
            if (count($address_reference_list) == 4) {
                $address->address_1 = $address_reference_list[0];
                $address->address_city = $address_reference_list[1];
                $address->address_state = $address_reference_list[2];
                $address->address_zip_code = $address_reference_list[3];
            }
            if (count($address_reference_list) == 3) {
                $address->address_1 = $address_reference_list[0];
                $address->address_city = $address_reference_list[1];
                $address->address_state = $address_reference_list[2];
            }
            if (count($address_reference_list) == 2) {
                $address->address_1 = $address_reference_list[0];
                $address->address_city = $address_reference_list[1];
            }
            if (count($address_reference_list) == 1) {
                $address->address_1 = $address_reference_list[0];                
            }
            $address->address_type = "Mailing Address";            
            $location_address_info = $address->address_recordid;
            $address->save();           
        }
                  
        $location_address_info = Location::where('location_address', '=', $location_address_info)->first();
        if($location_address_info != Null) {
            $location_recordid = $location_address_info["location_recordid"];
            $organization->organization_locations = $location_recordid;
        }
        else {
            $location = new Location;
            $new_recordid = strval(rand(533970821619432, 999999999999999));            
            if (in_array($new_recordid, $location_recordid_list)) {
                $new_recordid = strval(rand(533970821619432, 999999999999999));
            }            
            $location->location_recordid = $new_recordid;       
            $organization->organization_locations = $location->location_recordid;
            $location->save();           
        }
        
        $organization->organization_description = $request->organization_organization_comments;        
        $organization->flag = 'modified';
        $organization->save();

        return redirect('organization/'.$id);
    }

    public function add_new_organization(Request $request)
    {
        $organization = new Organization;
        $organization->organization_name = $request->organization_organization_name;
        $organization->organization_id = $request->organization_organization_id;
        $organization->organization_religion = $request->organization_organization_religion;
        $organization->organization_alt_id = $request->organization_organization_id;
        $organization->organization_faith_tradition = $request->organization_organization_faith_tradition;
        $organization->organization_denomination = $request->organization_organization_denomination;
        $organization->organization_judicatory_body = $request->organization_organization_judicatory_body;
        $organization->organization_type = $request->organization_organization_type;
        $organization->organization_url = $request->organization_organization_website;
        $organization->organization_facebook = $request->organization_organization_facebook;
        $organization->organization_c_board = $request->organization_organization_c_board; 
        if ($request->organization_organization_internete_access == 'on') {
            $organization->organization_internet_access = "yes";
        }
        else {
            $organization->organization_internet_access = "no";
        }
        $organization_facility_address = $request->organization_organization_facility_address;
        $organization->organization_description = $request->organization_organization_comments;        
        $organization->flag = 'modified';

        $address_recordids = Address::select("address_recordid")->distinct()->get();                 
        $address_recordid_list = array();
        foreach ($address_recordids as $key => $value) {
            $address_recordid = $value->location_recordid;
            array_push($address_recordid_list, $address_recordid);
        }
        
        $location_recordids = Location::select("location_recordid")->distinct()->get();                 
        $location_recordid_list = array();
        foreach ($location_recordids as $key => $value) {
            $location_recordid = $value->location_recordid;
            array_push($location_recordid_list, $location_recordid);
        }
        $location_recordid_list = array_unique($location_recordid_list);       
        $address = Address::where('address', '=', $organization_facility_address)->first();
        if($address != Null) {
            $address_id = $address["address_recordid"];
            $location_address_info = $address_id;
        }
        else {
            $address = new Address;
            $new_recordid = strval(rand(5339708216194320, 9999999999999990));            
            if (in_array($new_recordid, $address_recordid_list)) {
                $new_recordid = strval(rand(5339708216194320, 9999999999999990));
            }            
            $address->address_recordid = $new_recordid;
            $address->address = $organization_facility_address; 
            $address_reference_list = explode (",", $organization_facility_address);
            if (count($address_reference_list) == 4) {
                $address->address_1 = $address_reference_list[0];
                $address->address_city = $address_reference_list[1];
                $address->address_state = $address_reference_list[2];
                $address->address_zip_code = $address_reference_list[3];
            }
            if (count($address_reference_list) == 3) {
                $address->address_1 = $address_reference_list[0];
                $address->address_city = $address_reference_list[1];
                $address->address_state = $address_reference_list[2];
            }
            if (count($address_reference_list) == 2) {
                $address->address_1 = $address_reference_list[0];
                $address->address_city = $address_reference_list[1];
            }
            if (count($address_reference_list) == 1) {
                $address->address_1 = $address_reference_list[0];                
            }
            $address->address_type = "Mailing Address";            
            $location_address_info = $address->address_recordid;
            $address->save();           
        }
                  
        $location_address_info = Location::where('location_address', '=', $location_address_info)->first();
        if($location_address_info != Null) {
            $location_recordid = $location_address_info["location_recordid"];
            $organization->organization_locations = $location_recordid;
        }
        else {
            $location = new Location;
            $new_recordid = strval(rand(533970821619432, 999999999999999));            
            if (in_array($new_recordid, $location_recordid_list)) {
                $new_recordid = strval(rand(533970821619432, 999999999999999));
            }            
            $location->location_recordid = $new_recordid;       
            $organization->organization_locations = $location->location_recordid;
            $location->save();           
        }

        $organization_recordids = Organization::select("organization_recordid")->distinct()->get();                 
        $organization_recordid_list = array();
        foreach ($organization_recordids as $key => $value) {
            $organization_recordid = $value->organization_recordid;
            array_push($organization_recordid_list, $organization_recordid);
        }
        $organization_recordid_list = array_unique($organization_recordid_list); 

        $new_recordid = strval(rand(533970821000610943, 999999999000099999));            
        if (in_array($new_recordid, $organization_recordid_list)) {
            $new_recordid = strval(rand(533970821610000943, 999900099909999999));
        }            
        $organization->organization_recordid = $new_recordid;       

        $organization->save();

        return redirect('organizations');
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

    public function delete_organization(Request $request){
        $organization_recordid = $request->input('organization_recordid');       
        $organization = Organization::where('organization_recordid', '=', $organization_recordid)->first();
        if ($organization != NULL) {
            $organization->delete();
        }    
        return redirect('organizations');
    }
}
