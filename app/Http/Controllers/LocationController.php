<?php

namespace App\Http\Controllers;

use App\Address;
use App\Airtables;
use App\Comment;
use App\CSV;
use App\Functions\Airtable;
use App\Http\Controllers\Controller;
use App\Layout;
use App\Location;
use App\Locationaddress;
use App\Locationhistory;
use App\Locationphone;
use App\Locationschedule;
use App\Map;
use App\Model\facilityType;
use App\Organization;
use App\Services\Stringtoint;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PDF;
use Sentinel;

class LocationController extends Controller
{

    public function airtable()
    {

        Location::truncate();
        Locationaddress::truncate();
        Locationphone::truncate();
        Locationschedule::truncate();

        $airtable = new Airtable(array(
            'api_key' => env('AIRTABLE_API_KEY'),
            'base' => env('AIRTABLE_BASE_URL'),
        ));

        $request = $airtable->getContent('locations');

        do {

            $response = $request->getResponse();

            $airtable_response = json_decode($response, true);
            //var_dump($airtable_response);

            foreach ($airtable_response['records'] as $record) {

                $location = new Location();
                $strtointclass = new Stringtoint();
                $location->location_recordid = $strtointclass->string_to_int($record['id']);
                $location->location_name = isset($record['fields']['name']) ? $record['fields']['name'] : null;

                if (isset($record['fields']['organization'])) {
                    $i = 0;
                    foreach ($record['fields']['organization'] as $value) {

                        $locationorganization = $strtointclass->string_to_int($value);

                        if ($i != 0) {
                            $location->location_organization = $location->location_organization . ',' . $locationorganization;
                        } else {
                            $location->location_organization = $locationorganization;
                        }

                        $i++;
                    }
                }

                if (isset($record['fields']['contacts'])) {

                    $i = 0;
                    foreach ($record['fields']['contacts'] as $value) {

                        $locationcontact = $strtointclass->string_to_int($value);
                        if ($i != 0) {
                            $location->location_contact = $location->location_contact . ',' . $locationcontact;
                        } else {
                            $location->location_contact = $locationcontact;
                        }

                        $i++;
                    }
                }

                $location->location_id = isset($record['fields']['id']) ? $record['fields']['id'] : null;

                $location->location_type = isset($record['fields']['type']) ? $record['fields']['type'] : null;
                $location->location_latitude = isset($record['fields']['latitude']) ? $record['fields']['latitude'] : null;
                $location->location_longitude = isset($record['fields']['longitude']) ? $record['fields']['longitude'] : null;

                if (isset($record['fields']['address'])) {
                    $i = 0;
                    foreach ($record['fields']['address'] as $value) {
                        $locationaddress = new Locationaddress();
                        $locationaddress->location_recordid = $location->location_recordid;
                        $locationaddress->address_recordid = $strtointclass->string_to_int($value);
                        $locationaddress->save();
                        if ($i != 0) {
                            $location->location_address = $location->location_address . ',' . $locationaddress->address_recordid;
                        } else {
                            $location->location_address = $locationaddress->address_recordid;
                        }

                        $i++;
                    }
                }

                $location->location_congregation = isset($record['fields']['# congregations-z']) ? $record['fields']['# congregations-z'] : null;

                $location->location_building_status = isset($record['fields']['building status-z']) ? $record['fields']['building status-z'] : null;

                $location->location_call = isset($record['fields']['call-z']) ? $record['fields']['call-z'] : null;

                $location->location_description = isset($record['fields']['description']) ? $record['fields']['description'] : null;

                if (isset($record['fields']['services'])) {
                    $i = 0;
                    foreach ($record['fields']['services'] as $value) {

                        $locationservice = $strtointclass->string_to_int($value);

                        if ($i != 0) {
                            $location->location_services = $location->location_services . ',' . $locationservice;
                        } else {
                            $location->location_services = $locationservice;
                        }

                        $i++;
                    }
                }

                // $location->location_contact = isset($record['fields']['contact'])?$record['fields']['contact']:null;

                if (isset($record['fields']['details'])) {
                    $i = 0;
                    foreach ($record['fields']['details'] as $value) {
                        $locationdetail = $strtointclass->string_to_int($value);
                        if ($i != 0) {
                            $location->location_details = $location->location_details . ',' . $locationdetail;
                        } else {
                            $location->location_details = $locationdetail;
                        }

                        $i++;
                    }
                }

                $location->save();

            }

        } while ($request = $response->next());

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Locations')->first();
        $airtable->records = Location::count();
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
        // $locations = Location::with('organization')->orderBy('location_recordid')->paginate(20);
        // $source_data = Source_data::find(1);

        // return view('backEnd.tables.tb_location', compact('locations', 'source_data'));

        // $facilities = Location::orderBy('location_recordid', 'DESC')
        // ->paginate(200);

        // $location_types = Location::select("location_type")->distinct()->get();
        $location_types = facilityType::pluck('facility_type', 'id')->filter();
        $facilities = Location::orderBy('id', 'desc')->get();
        // $address_addresses = Address::select("address_1")->distinct()->get();
        $address_cities = Address::select("address_city")->distinct()->get();
        $address_zipcodes = Address::select("address_zip_code")->distinct()->get();
        $locations = Location::with('services', 'address', 'phones')->distinct()->get();
        $location_tags = Location::select("location_tag")->distinct()->get();
        $location_city_council_districts = Location::select("location_city_council_district")->distinct()->get();
        $location_community_districts = Location::select("location_community_district")->distinct()->get();

        $tag_list = Location::pluck("location_tag", "location_tag")->filter();
        // $tag_list = [];
        // foreach ($location_tags as $key => $value) {
        //     $tags = explode(", ", trim($value->location_tag));
        //     $tag_list = array_merge($tag_list, $tags);
        // }
        // $tag_list = array_unique($tag_list);

        $city_council_district_list = Location::pluck("location_city_council_district", "location_city_council_district")->filter();
        // $city_council_district_list = [];
        // foreach ($location_city_council_districts as $key => $value) {
        //     $city_council_districts = explode(", ", trim($value->location_city_council_district));
        //     $city_council_district_list = array_merge($city_council_district_list, $city_council_districts);
        // }
        // $city_council_district_list = array_unique($city_council_district_list);

        $community_district_list = Location::pluck("location_community_district", "location_community_district")->filter();
        // $community_district_list = [];
        // foreach ($location_community_districts as $key => $value) {
        //     $community_districts = explode(", ", trim($value->location_community_district));
        //     $community_district_list = array_merge($community_district_list, $community_districts);
        // }
        // $community_district_list = array_unique($community_district_list);

        $address_address_list = Address::pluck("address_1", 'address_1')->filter()->filter();
        // $address_address_list = [];
        // foreach ($address_addresses as $key => $value) {
        //     $address = explode(", ", trim($value->address_1));
        //     $address_address_list = array_merge($address_address_list, $address);
        // }
        // $address_address_list = array_unique($address_address_list);

        $address_city_list = Address::pluck("address_city", "address_city")->filter();
        // $address_city_list = [];
        // foreach ($address_cities as $key => $value) {
        //     $cities = explode(", ", trim($value->address_city));
        //     $address_city_list = array_merge($address_city_list, $cities);
        // }
        // $address_city_list = array_unique($address_city_list);

        $address_zipcode_list = Address::pluck("address_zip_code", "address_zip_code")->filter();
        // $address_zipcode_list = [];
        // foreach ($address_zipcodes as $key => $value) {
        //     $zipcodes = explode(", ", trim($value->address_zip_code));
        //     $address_zipcode_list = array_merge($address_zipcode_list, $zipcodes);
        // }
        // $address_zipcode_list = array_unique($address_zipcode_list);

        $map = Map::find(1);

        return view('frontEnd.locations', compact('locations', 'city_council_district_list', 'community_district_list',
            'location_types', 'address_address_list', 'address_city_list', 'address_zipcode_list', 'tag_list', 'map', 'facilities'));

    }

    public function get_all_facilities(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $search_term = $request->search_term;

        $filter_address = $request->filter_address;
        $filter_borough = $request->filter_borough;
        $filter_zipcode = $request->filter_zipcode;
        $filter_type = $request->filter_type;
        $filter_tag = $request->filter_tag;
        $filter_city_council_district = $request->filter_city_council_district;
        $filter_community_district = $request->filter_community_district;
        $filter_map = $request->filter_map;

        $facilities = Location::orderBy('location_recordid', 'DESC');

        if ($search_term) {
            $facilities = $facilities
                ->where('location_name', 'LIKE', '%' . $search_term . '%')
                ->orWhere('location_description', 'LIKE', '%' . $search_term . '%')
                ->orWhere('location_type', 'LIKE', '%' . $search_term . '%')
                ->orWhere('location_tag', 'LIKE', '%' . $search_term . '%')
                ->whereHas('organization', function (Builder $query) use ($search_term) {
                    $query->where('organization_name', 'LIKE', '%' . $search_term . '%');
                });
        }

        $filter_address_list = [];
        $filter_borough_list = [];
        $filter_zipcode_list = [];
        $filter_type_list = [];
        $filter_city_council_district_list = [];
        $filter_community_district_list = [];
        $filter_map_list = [];

        if ($filter_address) {
            $filter_address_list = explode('|', $filter_address);
        }
        if ($filter_borough) {
            $filter_borough_list = explode('|', $filter_borough);
        }
        if ($filter_zipcode) {
            $filter_zipcode_list = explode('|', $filter_zipcode);
        }
        if ($filter_type) {
            $filter_type_list = explode('|', $filter_type);
        }
        if ($filter_city_council_district) {
            $filter_city_council_district_list = explode('|', $filter_city_council_district);
        }
        if ($filter_community_district) {
            $filter_community_district_list = explode('|', $filter_community_district);
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
            foreach ($filter_map_list as $key => $filter_map_value) {
                if ($key == 0) {
                    continue;
                }

                $lat = round($filter_map_value->lat, 7);
                $lng = round($filter_map_value->lng, 7);
                $query = $query->orWhere(function ($q) use ($lat, $lng) {
                    $q->where('location_latitude', $lat)
                        ->where('location_longitude', $lng);
                });
            }
            $filtered_location_recordid_list = $query->pluck('location_recordid')->toArray();

        }

        if ($filter_type_list) {
            $facilities = $facilities->whereIn('location_type', $filter_type_list);
        }
        if ($filter_city_council_district_list) {
            $facilities = $facilities->whereIn('location_city_council_district', $filter_city_council_district_list);
        }
        if ($filter_community_district_list) {
            $facilities = $facilities->whereIn('location_community_district', $filter_community_district_list);
        }
        if ($filter_tag) {
            $facilities = $facilities->where('location_tag', 'LIKE', '%' . $filter_tag . '%');
        }

        if ($filtered_location_recordid_list) {
            $facilities = $facilities->whereIn('location_recordid', $filtered_location_recordid_list);
        }

        if ($filter_address_list || $filter_borough_list || $filter_zipcode_list) {
            $query = Address::orderBy('address_recordid');
            if ($filter_zipcode_list) {
                $query = $query->whereIn('address_zip_code', $filter_zipcode_list);
            }
            if ($filter_borough_list) {
                $query = $query->whereIn('address_city', $filter_borough_list);
            }
            if ($filter_address_list) {
                $query = $query->whereIn('address_1', $filter_address_list);
            }
            $filtered_address_ids = $query->pluck('address_recordid')->toArray();
            $facilities = $facilities->whereIn('location_address', $filtered_address_ids);

        }

        if ($filter_type_list || $filter_address_list || $filter_borough_list || $filter_zipcode_list || $filtered_location_recordid_list) {
            $filtered_locations_list = $facilities->get();
        } else {
            $filtered_locations_list = Location::with('services', 'address', 'phones')->distinct()->get();
        }

        $filtered_count = $facilities->count();

        $facilities = $facilities->offset($start)->limit($length)->get();
        $total_count = Location::count();
        $result = [];
        $facility_info = [];
        foreach ($facilities as $facility) {
            $facility_info[0] = '';
            $facility_info[1] = '';
            $facility_info[2] = $facility->location_recordid;
            $facility_info[3] = $facility->organization['organization_name'];

            $facility_full_address_info = '';
            if (isset($facility->address[0])) {
                $facility_full_address_info = $facility_full_address_info . $facility->address[0]['address_1'];
                if ($facility->address[0]['address_city']) {
                    $facility_full_address_info = $facility_full_address_info . ', ' . $facility->address[0]['address_city'];
                }
                if ($facility->address[0]['address_state']) {
                    $facility_full_address_info = $facility_full_address_info . ', ' . $facility->address[0]['address_state'];
                }
                if ($facility->address[0]['address_zip_code']) {
                    $facility_full_address_info = $facility_full_address_info . ', ' . $facility->address[0]['address_zip_code'];
                }
            }

            $facility_info[4] = $facility_full_address_info;
            $facility_info[5] = $facility->location_congregation;
            $facility_info[6] = $facility->location_building_status;
            $facility_info[7] = $facility->location_call;
            $facility_info[8] = $facility->location_name;
            $facility_info[9] = $facility->location_type;

            $facility_info[10] = '';
            if (isset($facility->address[0])) {
                $facility_info[10] = $facility->address[0]['address_zip_code'];
            }
            $facility_info[11] = '';
            if (isset($facility->address[0])) {
                $facility_info[11] = $facility->address[0]['address_city'];
            }
            $facility_info[12] = $facility->location_description;
            $facility_info[13] = $facility->location_tag;
            $facility_info[14] = $facility->location_city_council_district;
            $facility_info[15] = $facility->location_community_district;

            array_push($result, $facility_info);
        }
        return response()->json(array('data' => $result, 'recordsTotal' => $total_count, 'recordsFiltered' => $filtered_count, 'filtered_locations_list' => $filtered_locations_list));
    }

    public function group_operation(Request $request)
    {
        switch ($request->input('btn_submit')) {
            case 'download_csv':

                $facilities = Location::orderBy('location_recordid', 'DESC');

                $filter_address = $request->input('address_list');
                $filter_borough = $request->input('borough_list');
                $filter_zipcode = $request->input('zipcode_list');
                $filter_type = $request->input('type_list');

                $filter_address_list = [];
                $filter_borough_list = [];
                $filter_zipcode_list = [];
                $filter_type_list = [];

                if ($filter_address) {
                    $filter_address_list = explode(',', $filter_address);
                }
                if ($filter_borough) {
                    $filter_borough_list = explode(',', $filter_borough);
                }
                if ($filter_zipcode) {
                    $filter_zipcode_list = explode(',', $filter_zipcode);
                }
                if ($filter_type) {
                    $filter_type_list = explode(',', $filter_type);
                }
                if ($filter_type_list) {
                    $facilities = $facilities->whereIn('location_type', $filter_type_list);
                }

                if ($filter_address_list || $filter_borough_list || $filter_zipcode_list) {
                    $query = Address::orderBy('address_recordid');
                    if ($filter_zipcode_list) {
                        $query = $query->whereIn('address_zip_code', $filter_zipcode_list);
                    }
                    if ($filter_borough_list) {
                        $query = $query->whereIn('address_city', $filter_borough_list);
                    }
                    if ($filter_address_list) {
                        $query = $query->whereIn('address_1', $filter_address_list);
                    }
                    $filtered_address_ids = $query->pluck('address_recordid')->toArray();
                    $facilities = $facilities->whereIn('location_address', $filtered_address_ids);

                }

                $facilities = $facilities->get();

                $csvExporter = new \Laracsv\Export();

                $csv = CSV::find(1);
                $layout = Layout::find(1);
                $source = $layout->footer_csv;
                $csv->description = $source;
                $csv->save();

                $csv = CSV::all();

                return $csvExporter->build($facilities, [
                    'location_name' => 'Facility Name', 'location_type' => 'Facility Type',
                    'location_congregation' => 'Congregation', 'location_building_status' => 'Building Status',
                    'location_call' => 'Call'])
                    ->build($csv, ['description' => ''])
                    ->download();

                break;

            case 'download_pdf':
                $facilities = Location::orderBy('location_recordid', 'DESC');

                $filter_address = $request->input('address_list');
                $filter_borough = $request->input('borough_list');
                $filter_zipcode = $request->input('zipcode_list');
                $filter_type = $request->input('type_list');

                $filter_address_list = [];
                $filter_borough_list = [];
                $filter_zipcode_list = [];
                $filter_type_list = [];

                if ($filter_address) {
                    $filter_address_list = explode(',', $filter_address);
                }
                if ($filter_borough) {
                    $filter_borough_list = explode(',', $filter_borough);
                }
                if ($filter_zipcode) {
                    $filter_zipcode_list = explode(',', $filter_zipcode);
                }
                if ($filter_type) {
                    $filter_type_list = explode(',', $filter_type);
                }
                if ($filter_type_list) {
                    $facilities = $facilities->whereIn('location_type', $filter_type_list);
                }

                if ($filter_address_list || $filter_borough_list || $filter_zipcode_list) {
                    $query = Address::orderBy('address_recordid');
                    if ($filter_zipcode_list) {
                        $query = $query->whereIn('address_zip_code', $filter_zipcode_list);
                    }
                    if ($filter_borough_list) {
                        $query = $query->whereIn('address_city', $filter_borough_list);
                    }
                    if ($filter_address_list) {
                        $query = $query->whereIn('address_1', $filter_address_list);
                    }
                    $filtered_address_ids = $query->pluck('address_recordid')->toArray();
                    $facilities = $facilities->whereIn('location_address', $filtered_address_ids);

                }

                $facilities = $facilities->get();
                $layout = Layout::find(1);
                set_time_limit(0);
                $pdf = PDF::loadView('frontEnd.locations_download', compact('facilities', 'layout'));
                return $pdf->download('locations.pdf');

                break;
        }
    }

    public function facilities()
    {
        // // $facilities = Location::orderBy('location_recordid', 'DESC')
        // // ->paginate(200);

        // // $location_types = Location::select("location_type")->distinct()->get();
        // $location_types = facilityType::pluck('facility_type', 'facility_type');

        // $address_addresses = Address::select("address_1")->distinct()->get();
        // $address_cities = Address::select("address_city")->distinct()->get();
        // $address_zipcodes = Address::select("address_zip_code")->distinct()->get();
        // $locations = Location::with('services', 'address', 'phones')->distinct()->get();
        // $location_tags = Location::select("location_tag")->distinct()->get();
        // $location_city_council_districts = Location::select("location_city_council_district")->distinct()->get();
        // $location_community_districts = Location::select("location_community_district")->distinct()->get();

        // $tag_list = [];
        // foreach ($location_tags as $key => $value) {
        //     $tags = explode(", " , trim($value->location_tag));
        //     $tag_list = array_merge($tag_list, $tags);
        // }
        // $tag_list = array_unique($tag_list);

        // $city_council_district_list = [];
        // foreach ($location_city_council_districts as $key => $value) {
        //     $city_council_districts = explode(", " , trim($value->location_city_council_district));
        //     $city_council_district_list = array_merge($city_council_district_list, $city_council_districts);
        // }
        // $city_council_district_list = array_unique($city_council_district_list);

        // $community_district_list = [];
        // foreach ($location_community_districts as $key => $value) {
        //     $community_districts = explode(", " , trim($value->location_community_district));
        //     $community_district_list = array_merge($community_district_list, $community_districts);
        // }
        // $community_district_list = array_unique($community_district_list);

        // $address_address_list = [];
        // foreach ($address_addresses as $key => $value) {
        //     $address = explode(", " , trim($value->address_1));
        //     $address_address_list = array_merge($address_address_list, $address);
        // }
        // $address_address_list = array_unique($address_address_list);

        // $address_city_list = [];
        // foreach ($address_cities as $key => $value) {
        //     $cities = explode(", " , trim($value->address_city));
        //     $address_city_list = array_merge($address_city_list, $cities);
        // }
        // $address_city_list = array_unique($address_city_list);

        // $address_zipcode_list = [];
        // foreach ($address_zipcodes as $key => $value) {
        //     $zipcodes = explode(", " , trim($value->address_zip_code));
        //     $address_zipcode_list = array_merge($address_zipcode_list, $zipcodes);
        // }
        // $address_zipcode_list = array_unique($address_zipcode_list);

        // $map = Map::find(1);

        // return view('frontEnd.locations', compact('locations', 'city_council_district_list', 'community_district_list',
        // 'location_types', 'address_address_list', 'address_city_list', 'address_zipcode_list', 'tag_list', 'map'));
    }

    public function tagging(Request $request, $id)
    {
        $location = Location::find($id);
        $location->location_tag = $request->tokenfield;
        $location->save();
        return redirect('facility/' . $id);
    }

    public function facility($id)
    {
        $facility = Location::where('location_recordid', '=', $id)->first();
        $facility_history_list = Locationhistory::where('location_recordid', '=', $id)->get();
        $locations = Location::with('services', 'address', 'phones')->where('location_recordid', '=', $id)->get();

        $organization_id = $facility->location_organization;
        $organization_name_info = Organization::where('organization_recordid', '=', $organization_id)->select('organization_name')->first();
        $organization_name = $organization_name_info["organization_name"];

        $location_address = Location::where('location_recordid', '=', $id)->select('location_address')->first();
        $location_address_id = $location_address['location_address'];
        $address_name_info = Address::where('address_recordid', '=', $location_address_id)->select('address')->first();
        $address_name = $address_name_info['address'];

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
        $checked_hours = [];

        $comment_list = Comment::where('comments_location', '=', $id)->get();

        return view('frontEnd.location', compact('facility', 'locations', 'comment_list', 'organization_id', 'organization_name', 'address_name', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'facility_history_list'));
    }

    public function add_comment(Request $request, $id)
    {

        $facility = Location::find($id);
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
        $comment->comments_location = $id;
        $comment->comments_datetime = $date_time;
        $comment->save();

        $comment_list = Comment::where('comments_location', '=', $id)->get();

        return redirect('facility/' . $id);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $map = Map::find(1);
        $organization_names = Organization::pluck("organization_name", "organization_name");
        $address_addresses = Address::select("address_1")->distinct()->get();
        $address_cities = Address::select("address_city")->distinct()->get();
        $address_zipcodes = Address::select("address_zip_code")->distinct()->get();

        // $organization_name_list = [];
        // foreach ($organization_names as $key => $value) {
        //     $org_names = explode(", ", trim($value->organization_name));
        //     $organization_name_list = array_merge($organization_name_list, $org_names);
        // }
        // $organization_name_list = array_unique($organization_name_list);

        $address_address_list = [];
        foreach ($address_addresses as $key => $value) {
            $address = explode(", ", trim($value->address_1));
            $address_address_list = array_merge($address_address_list, $address);
        }
        $address_address_list = array_unique($address_address_list);

        $address_city_list = [];
        foreach ($address_cities as $key => $value) {
            $cities = explode(", ", trim($value->address_city));
            $address_city_list = array_merge($address_city_list, $cities);
        }
        $address_city_list = array_unique($address_city_list);

        $address_zipcode_list = [];
        foreach ($address_zipcodes as $key => $value) {
            $zipcodes = explode(", ", trim($value->address_zip_code));
            $address_zipcode_list = array_merge($address_zipcode_list, $zipcodes);
        }
        $address_zipcode_list = array_unique($address_zipcode_list);

        $facility_facility_type = facilityType::pluck('facility_type', 'id');

        $building_status_list = ['yes', 'no'];

        return view('frontEnd.location-create', compact('map',
            'address_address_list', 'address_city_list', 'address_zipcode_list', 'building_status_list', 'facility_facility_type', 'organization_names'));
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
        // $process = Location::find($id);
        // return response()->json($process);
        $facility = Location::where('location_recordid', '=', $id)->first();
        $facility_history_list = Locationhistory::where('location_recordid', '=', $id)->get();
        $locations = Location::with('services', 'address', 'phones')->where('location_recordid', '=', $id)->get();

        $organization_id = $facility->location_organization;
        $organization_name_info = Organization::where('organization_recordid', '=', $organization_id)->select('organization_name')->first();
        $organization_name = $organization_name_info["organization_name"];

        $location_address = Location::where('location_recordid', '=', $id)->select('location_address')->first();
        $location_address_id = $location_address['location_address'];
        $address_name_info = Address::where('address_recordid', '=', $location_address_id)->select('address')->first();
        $address_name = $address_name_info['address'];
        $organization_names = Organization::pluck("organization_name", "organization_recordid");

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
        $checked_hours = [];

        $comment_list = Comment::where('comments_location', '=', $id)->get();

        return view('frontEnd.location', compact('facility', 'locations', 'comment_list', 'organization_id', 'organization_name', 'address_name', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'facility_history_list', 'organization_names'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $map = Map::find(1);
        $facility = Location::where('location_recordid', '=', $id)->first();
        $organization_names = Organization::pluck("organization_name", "organization_recordid");
        $organization_id = $facility->location_organization;
        $organization_name_info = Organization::where('organization_recordid', '=', $organization_id)->select('organization_name')->first();
        $facility_organization_name = $organization_name_info["organization_name"];

        $address_addresses = Address::select("address_1")->distinct()->get();
        $address_cities = Address::select("address_city")->distinct()->get();
        $address_zipcodes = Address::select("address_zip_code")->distinct()->get();

        // $organization_name_list = [];
        // foreach ($organization_names as $key => $value) {
        //     $org_names = explode(", ", trim($value->organization_name));
        //     $organization_name_list = array_merge($organization_name_list, $org_names);
        // }
        // $organization_name_list = array_unique($organization_name_list);

        $address_address_list = [];
        foreach ($address_addresses as $key => $value) {
            $address = explode(", ", trim($value->address_1));
            $address_address_list = array_merge($address_address_list, $address);
        }
        $address_address_list = array_unique($address_address_list);

        $address_city_list = [];
        foreach ($address_cities as $key => $value) {
            $cities = explode(", ", trim($value->address_city));
            $address_city_list = array_merge($address_city_list, $cities);
        }
        $address_city_list = array_unique($address_city_list);

        $address_zipcode_list = [];
        foreach ($address_zipcodes as $key => $value) {
            $zipcodes = explode(", ", trim($value->address_zip_code));
            $address_zipcode_list = array_merge($address_zipcode_list, $zipcodes);
        }
        $address_zipcode_list = array_unique($address_zipcode_list);

        $facility_location_address = Location::where('location_recordid', '=', $id)->select('location_address')->first();
        $facility_location_address_id = $facility_location_address['location_address'];

        $address_city_info = Address::where('address_recordid', '=', $facility_location_address_id)->select('address_city')->first();
        $location_address_city = $address_city_info['address_city'];
        $address_street_address_info = Address::where('address_recordid', '=', $facility_location_address_id)->select('address_1')->first();
        $location_street_address = $address_street_address_info['address_1'];
        $address_zip_code_info = Address::where('address_recordid', '=', $facility_location_address_id)->select('address_zip_code')->first();
        $location_zip_code = $address_zip_code_info['address_zip_code'];

        $building_status_list = ['yes', 'no'];
        $facility_call_list = ['yes', 'no', 'unknown'];

        $facility_facility_type = facilityType::pluck('facility_type', 'id');

        return view('frontEnd.location-edit', compact('facility', 'map', 'facility_organization_name',
            'address_address_list', 'address_city_list', 'address_zipcode_list', 'location_address_city', 'location_street_address',
            'location_zip_code', 'building_status_list', 'facility_call_list', 'facility_facility_type', 'organization_names'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function add_new_facility(Request $request)
    {
        $location = new Location;
        $location->location_name = $request->facility_location_name;

        $address_recordids = Address::select("address_recordid")->distinct()->get();
        $address_recordid_list = array();
        foreach ($address_recordids as $key => $value) {
            $address_recordid = $value->address_recordid;
            array_push($address_recordid_list, $address_recordid);
        }
        $address_recordid_list = array_unique($address_recordid_list);

        $organization_name = $request->facility_organization_name;
        $facility_organization = Organization::where('organization_name', '=', $organization_name)->first();
        $facility_organization_id = $facility_organization["organization_recordid"];
        $location->location_organization = $facility_organization_id;

        $location->location_type = $request->facility_facility_type;
        // if ($request->facility_facility_type == '0') {
        //     $location->location_type = 'Faith-Based-Service Provider';
        // }
        // if ($request->facility_facility_type == '1') {
        //     $location->location_type = 'House of Worship';
        // }
        // if ($request->facility_facility_type == '2') {
        //     $location->location_type = 'Religious Shool';
        // }
        // if ($request->facility_facility_type == '3') {
        //     $location->location_type = 'Other';
        // }

        $location->location_congregation = $request->facility_congregation;

        $location->location_building_status = $request->facility_building_status;

        if ($request->facility_facility_call == 'on') {
            $location->location_call = 'yes';
        } else {
            $location->location_call = 'no';
        }

        $location->location_description = $request->facility_location_comments;

        $facility_address_city = $request->facility_address_city;
        $facility_street_address = $request->facility_street_address;
        $facility_address_state = 'NY';
        $facility_address_zip_code = $request->facility_zip_code;
        $facility_address_info = $facility_street_address . ', ' . $facility_address_city . ', ' . $facility_address_state . ', ' . $facility_address_zip_code;

        $address = Address::where('address', '=', $facility_address_info)->first();
        if ($address != null) {
            $address_id = $address["address_recordid"];
            $location->location_address = $address_id;
        } else {
            $address = new Address;
            $new_recordid = Address::orderBy('id', 'desc')->first();
            $new_recordid = strval(rand(533970821619432, 999999999999999));
            if (in_array($new_recordid, $address_recordid_list)) {
                $new_recordid = strval(rand(533970821619432, 999999999999999));
            }

            $address->address_recordid = $new_recordid;
            $address->address = $facility_address_info;
            $address->address_1 = $facility_street_address;
            $address->address_city = $facility_address_city;
            $address->address_state = $facility_address_state;
            $address->address_zip_code = $facility_address_zip_code;
            $address->address_type = "Mailing Address";
            $location->location_address = $new_recordid;
            $address->save();
        }

        $location->flag = 'modified';

        $location_recordids = Location::select("location_recordid")->distinct()->get();
        $location_recordid_list = array();
        foreach ($location_recordids as $key => $value) {
            $location_recordid = $value->location_recordid;
            array_push($location_recordid_list, $location_recordid);
        }
        $location_recordid_list = array_unique($location_recordid_list);

        $new_recordid = strval(rand(1349817267640634, 2349817267640634));
        if (in_array($new_recordid, $location_recordid_list)) {
            $new_recordid = strval(rand(1349817267640634, 2349817267640634));
        }

        $location->location_recordid = $location_recordid;
        // var_dump($new_recordid);

        $location->save();
        return redirect('facilities');
    }

    public function update(Request $request, $id)
    {
        $location = Location::find($id);
        // dd($location);
        if ($location->location_name != $request->facility_location_name) {
            $history = new Locationhistory();
            $history->location_recordid = $id;
            $history->fieldname_changed = "Facility Name";
            $history->old_value = $location->location_name;
            $history->new_value = $request->facility_location_name;
            $history->save();
        }
        $location->location_name = $request->facility_location_name;

        $address_recordids = Address::select("address_recordid")->distinct()->get();
        $address_recordid_list = array();
        foreach ($address_recordids as $key => $value) {
            $address_recordid = $value->address_recordid;
            array_push($address_recordid_list, $address_recordid);
        }
        $address_recordid_list = array_unique($address_recordid_list);

        $organization_name = $request->facility_organization_name;
        $facility_organization = Organization::where('organization_recordid', '=', $organization_name)->first();
        $facility_organization_id = $facility_organization["organization_recordid"];

        if ($location->location_organization != $facility_organization_id) {
            $history = new Locationhistory();
            $history->location_recordid = $id;
            $history->fieldname_changed = "Organization";
            $organization_name_old = Organization::where('organization_recordid', '=', $location->location_organization)->first();

            $organization_name_new = Organization::where('organization_recordid', '=', $facility_organization_id)->first()->organization_name;
            $history->old_value = $organization_name_old;
            $history->new_value = $organization_name_new;
            $history->save();
        }
        $location->location_organization = $facility_organization_id;

        $request_location_type = $request->facility_facility_type;
        // if ($request->facility_facility_type == '0') {
        //     $request_location_type = 'Faith-Based-Service Provider';
        // }
        // if ($request->facility_facility_type == '1') {
        //     $request_location_type = 'House of Worship';
        // }
        // if ($request->facility_facility_type == '2') {
        //     $request_location_type = 'Religious Shool';
        // }
        // if ($request->facility_facility_type == '3') {
        //     $request_location_type = 'Other';
        // }

        if ($location->location_type != $request_location_type) {
            $history = new Locationhistory();
            $history->location_recordid = $id;
            $history->fieldname_changed = "Location Type";
            $history->old_value = $location->location_type;
            $history->new_value = $request_location_type;
            $history->save();
        }
        $location->location_type = $request_location_type;

        if ($location->location_congregation != $request->facility_congregation) {
            $history = new Locationhistory();
            $history->location_recordid = $id;
            $history->fieldname_changed = "Congregation";
            $history->old_value = $location->location_congregation;
            $history->new_value = $request->facility_congregation;
            $history->save();
        }
        $location->location_congregation = $request->facility_congregation;

        if ($location->location_building_status != $request->facility_building_status) {
            $history = new Locationhistory();
            $history->location_recordid = $id;
            $history->fieldname_changed = "Building Status";
            $history->old_value = $location->location_building_status;
            $history->new_value = $request->facility_building_status;
            $history->save();
        }
        $location->location_building_status = $request->facility_building_status;

        if ($location->location_call != '-') {
            if ($location->location_call != $request->facility_facility_call) {
                $history = new Locationhistory();
                $history->location_recordid = $id;
                $history->fieldname_changed = "Call in Emergency";
                $history->old_value = $location->location_call;
                $history->new_value = $request->facility_facility_call;
                $history->save();
            }
        } else {
            if ($request->facility_facility_call != 'unknown') {
                $history = new Locationhistory();
                $history->location_recordid = $id;
                $history->fieldname_changed = "Call in Emergency";
                $history->old_value = 'unknown';
                $history->new_value = $request->facility_facility_call;
                $history->save();
            }
        }

        if ($request->facility_facility_call == 'unknown') {
            $location->location_call = '-';
        } else {
            $location->location_call = $request->facility_facility_call;
        }

        if ($location->location_description != $request->facility_location_comments) {
            $history = new Locationhistory();
            $history->location_recordid = $id;
            $history->fieldname_changed = "Description";
            $history->old_value = $location->location_description;
            $history->new_value = $request->facility_location_comments;
            $history->save();
        }
        $location->location_description = $request->facility_location_comments;

        $facility_address_city = $request->facility_address_city;
        $facility_street_address = $request->facility_street_address;
        $facility_address_state = 'NY';
        $facility_address_zip_code = $request->facility_zip_code;

        $old_address_recordid = $location->location_address;
        $old_address_info = Address::where('address_recordid', '=', $old_address_recordid)->first();
        $old_street_address = $old_address_info->address_1;
        $old_address_city = $old_address_info->address_city;
        $old_address_zip_code = $old_address_info->address_zip_code;

        if ($old_street_address != $facility_street_address) {
            $history = new Locationhistory();
            $history->location_recordid = $id;
            $history->fieldname_changed = "Street Address";
            $history->old_value = $old_street_address;
            $history->new_value = $facility_street_address;
            $history->save();
        }
        if ($old_address_city != $facility_address_city) {
            $history = new Locationhistory();
            $history->location_recordid = $id;
            $history->fieldname_changed = "Borough";
            $history->old_value = $old_address_city;
            $history->new_value = $facility_address_city;
            $history->save();
        }
        if ($old_address_zip_code != $facility_address_zip_code) {
            $history = new Locationhistory();
            $history->location_recordid = $id;
            $history->fieldname_changed = "Zip Code";
            $history->old_value = $old_address_zip_code;
            $history->new_value = $facility_address_zip_code;
            $history->save();
        }

        $facility_address_info = $facility_street_address . ', ' . $facility_address_city . ', ' . $facility_address_state . ', ' . $facility_address_zip_code;

        $address = Address::where('address', '=', $facility_address_info)->first();
        if ($address != null) {
            $address_id = $address["address_recordid"];
            $location->location_address = $address_id;
        } else {
            $address = new Address;
            $new_recordid = strval(rand(533970821619432, 999999999999999));
            if (in_array($new_recordid, $address_recordid_list)) {
                $new_recordid = strval(rand(533970821619432, 999999999999999));
            }

            $address->address_recordid = $new_recordid;
            $address->address = $facility_address_info;
            $address->address_1 = $facility_street_address;
            $address->address_city = $facility_address_city;
            $address->address_state = $facility_address_state;
            $address->address_zip_code = $facility_address_zip_code;
            $address->address_type = "Mailing Address";
            $location->location_address = $new_recordid;
            $address->save();
        }

        $location->flag = 'modified';

        $location->save();
        return redirect('facility/' . $id);
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

    public function delete_facility(Request $request)
    {
        $facility_recordid = $request->input('facility_recordid');
        $facility = Location::where('location_recordid', '=', $facility_recordid)->first();
        $facility->delete();

        return redirect('facilities');
    }
}
