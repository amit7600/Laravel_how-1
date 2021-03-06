<?php

namespace App\Http\Controllers;

use App\Address;
use App\Airtables;
use App\CampaignReport;
use App\Comment;
use App\Contact;
use App\CSV;
use App\Functions\Airtable;
use App\Group;
use App\Http\Controllers\Controller;
use App\Layout;
use App\Location;
use App\Map;
use App\Model\AllLanguage;
use App\Model\contactType;
use App\Model\Religion;
use App\Organization;
use App\Phone;
use App\Services\Stringtoint;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PDF;
use Sentinel;

class ContactController extends Controller
{

    public function airtable()
    {

        Contact::truncate();
        $airtable = new Airtable(array(
            'api_key' => env('AIRTABLE_API_KEY'),
            'base' => env('AIRTABLE_BASE_URL'),
        ));

        $request = $airtable->getContent('contact');

        do {

            $response = $request->getResponse();

            $airtable_response = json_decode($response, true);

            foreach ($airtable_response['records'] as $record) {

                $contact = new Contact();
                $strtointclass = new Stringtoint();

                $contact->contact_recordid = $strtointclass->string_to_int($record['id']);
                $contact->contact_id = isset($record['fields']['id']) ? $record['fields']['id'] : null;
                $contact->contact_first_name = isset($record['fields']['First Name-z']) ? $record['fields']['First Name-z'] : null;
                $contact->contact_middle_name = isset($record['fields']['Middle Name-z']) ? $record['fields']['Middle Name-z'] : null;
                $contact->contact_last_name = isset($record['fields']['Last Name-z']) ? $record['fields']['Last Name-z'] : null;

                if (isset($record['fields']['organizations'])) {
                    $i = 0;
                    foreach ($record['fields']['organizations'] as $value) {
                        $contactorganization = $strtointclass->string_to_int($value);

                        if ($i != 0) {
                            $contact->contact_organizations = $contact->contact_organizations . ',' . $contactorganization;
                        } else {
                            $contact->contact_organizations = $contactorganization;
                        }

                        $i++;
                    }
                }

                $contact->contact_organization_id = isset($record['fields']['Organization ID-z']) ? implode(",", $record['fields']['Organization ID-z']) : null;

                $contactType = isset($record['fields']['type-z']) ? $record['fields']['type-z'] : null;

                $contact_type = contactType::where('contact_type', 'LIKE', '%' . $contactType . '%')->first();

                $contact->contact_type = isset($contact_type) ? intval($contact_type->id) : null;

                // $contact->contact_type = isset($record['fields']['type-z']) ? $record['fields']['type-z'] : null;

                $languageData = isset($record['fields']['languages spoken-z']) ? $record['fields']['languages spoken-z'] : [];
                $languageId = [];
                if (count($languageData) > 0) {
                    foreach ($languageData as $key => $languageName) {
                        $AllLanguage = AllLanguage::where('language_name', 'LIKE', '%' . $contactType . '%')->first();
                        if ($AllLanguage) {
                            array_push($languageId, $AllLanguage->id);
                        }
                    }
                }

                // $contact->contact_languages_spoken = isset($record['fields']['languages spoken-z']) ? implode(",", $record['fields']['languages spoken-z']) : null;

                $contact->contact_languages_spoken = count($languageId) > 0 ? implode(",", $languageId) : null;

                $contact->contact_other_languages = isset($record['fields']['other languages-z']) ? $record['fields']['other languages-z'] : null;
                $contact->contact_religious_title = isset($record['fields']['religious title-z']) ? $record['fields']['religious title-z'] : null;
                $contact->contact_title = isset($record['fields']['title']) ? $record['fields']['title'] : null;
                $contact->contact_pronouns = isset($record['fields']['pronouns-z']) ? $record['fields']['pronouns-z'] : null;

                if (isset($record['fields']['mailing address-z'])) {
                    $i = 0;
                    foreach ($record['fields']['mailing address-z'] as $value) {
                        $contact_mailing_address = $strtointclass->string_to_int($value);

                        if ($i != 0) {
                            $contact->contact_mailing_address = $contact->contact_mailing_address . ',' . $contact_mailing_address;
                        } else {
                            $contact->contact_mailing_address = $contact_mailing_address;
                        }

                        $i++;
                    }
                }

                if (isset($record['fields']['cell-phones-y'])) {
                    $i = 0;
                    foreach ($record['fields']['cell-phones-y'] as $value) {
                        $contact_cell_phones = $strtointclass->string_to_int($value);

                        if ($i != 0) {
                            $contact->contact_cell_phones = $contact->contact_cell_phones . ',' . $contact_cell_phones;
                        } else {
                            $contact->contact_cell_phones = $contact_cell_phones;
                        }

                        $i++;
                    }
                }
                if (isset($record['fields']['office-phones-y'])) {
                    $i = 0;
                    foreach ($record['fields']['office-phones-y'] as $value) {
                        $contact_office_phones = $strtointclass->string_to_int($value);

                        if ($i != 0) {
                            $contact->contact_office_phones = $contact->contact_office_phones . ',' . $contact_office_phones;
                        } else {
                            $contact->contact_office_phones = $contact_office_phones;
                        }

                        $i++;
                    }
                }
                if (isset($record['fields']['emergency-phones-y'])) {
                    $i = 0;
                    foreach ($record['fields']['emergency-phones-y'] as $value) {
                        $contact_emergency_phones = $strtointclass->string_to_int($value);

                        if ($i != 0) {
                            $contact->contact_emergency_phones = $contact->contact_emergency_phones . ',' . $contact_emergency_phones;
                        } else {
                            $contact->contact_emergency_phones = $contact_emergency_phones;
                        }

                        $i++;
                    }
                }
                if (isset($record['fields']['office-fax-phones-y'])) {
                    $i = 0;
                    foreach ($record['fields']['office-fax-phones-y'] as $value) {
                        $contact_office_fax_phones = $strtointclass->string_to_int($value);

                        if ($i != 0) {
                            $contact->contact_office_fax_phones = $contact->contact_office_fax_phones . ',' . $contact_office_fax_phones;
                        } else {
                            $contact->contact_office_fax_phones = $contact_office_fax_phones;
                        }

                        $i++;
                    }
                }

                $contact->contact_personal_email = isset($record['fields']['personal email-z']) ? $record['fields']['personal email-z'] : null;

                $contact->contact_email = isset($record['fields']['email']) ? $record['fields']['email'] : null;

                $contact->save();

            }

        } while ($request = $response->next());

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Contact')->first();
        $airtable->records = Contact::count();
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
        // $contacts = Contact::orderBy('contact_recordid')->paginate(20);
        // $source_data = Source_data::find(1);

        // return view('backEnd.tables.tb_contacts', compact('contacts', 'source_data'));

        // $contacts = Contact::orderBy('contact_recordid', 'DESC')
        //     ->paginate(200);

        $address_addresses = Address::select("address_1")->distinct()->get();
        $address_cities = Address::select("address_city")->distinct()->get();
        $address_zipcodes = Address::select("address_zip_code")->distinct()->get();

// $contact_types = Contact::select("contact_type")->distinct()->get();
        $contact_types = contactType::pluck('contact_type', 'id');

// $contact_languages = Contact::select("contact_languages_spoken")->distinct()->get();
        $contact_languages = AllLanguage::pluck('language_name', 'id');

        $contact_addresses = Contact::select("contact_mailing_address")->distinct()->get();
// $organization_religions = Organization::select("organization_religion")->distinct()->get();
        $organization_religions = Religion::where('type', 'religion')->pluck('name', 'id');
// $organization_faith_traditions = Organization::select("organization_faith_tradition")->distinct()->get();
        $faith_tradition_list = Religion::where('type', 'faith_tradition')->pluck('name', 'id');

// $organization_denominations = Organization::select("organization_denomination")->distinct()->get();
        $denomination_list = Religion::where('type', 'denominations')->pluck('name', 'id');

// $organization_judicatory_bodys = Organization::select("organization_judicatory_body")->distinct()->get();
        $organization_judicatory_bodys = Religion::where('type', 'judicatory_body')->pluck('name', 'id');

        $organization_boroughs = Organization::select("organization_borough")->distinct()->get();
        $organization_zipcodes = Organization::select("organization_zipcode")->distinct()->get();
        $contact_tags = Contact::select("contact_tag")->distinct()->get();
        $locations = Location::with('services', 'address', 'phones')->distinct()->get();

        $tag_list = [];
        foreach ($contact_tags as $key => $value) {
            $tags = explode(", ", trim($value->contact_tag));
            $tag_list = array_merge($tag_list, $tags);
        }
        $tag_list = array_unique($tag_list);

        $address_address_list = [];
        foreach ($address_addresses as $key => $value) {
            $addresses = explode(", ", trim($value->address_1));
            $address_address_list = array_merge($address_address_list, $addresses);
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

// $faith_tradition_list = [];
        // foreach ($organization_faith_traditions as $key => $value) {
        //     $faith_traditions = explode(", ", trim($value->organization_faith_tradition));
        //     $faith_tradition_list = array_merge($faith_tradition_list, $faith_traditions);
        // }
        // $faith_tradition_list = array_unique($faith_tradition_list);

// $denomination_list = [];
        // foreach ($organization_denominations as $key => $value) {
        //     $denominations = explode(", ", trim($value->organization_denomination));
        //     $denomination_list = array_merge($denomination_list, $denominations);
        // }
        // $denomination_list = array_unique($denomination_list);

        $map = Map::find(1);

        $contacts = Contact::get();
        $languages = AllLanguage::get();

        return view('frontEnd.contacts', compact('contact_types', 'contact_languages', 'contact_addresses', 'organization_religions', 'organization_judicatory_bodys'
            , 'organization_boroughs', 'organization_zipcodes', 'address_address_list', 'address_city_list', 'address_zipcode_list',
            'map', 'locations', 'faith_tradition_list', 'denomination_list', 'tag_list', 'contacts', 'languages'));

    }

    public function get_all_contacts(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $search_term = $request->search_term;
        $filter_contact_borough = $request->filter_contact_borough;
        $filter_contact_zipcode = $request->filter_contact_zipcode;
        $filter_contact_languages = $request->filter_contact_languages;
        $filter_contact_address = $request->filter_contact_address;
        $filter_contact_type = $request->filter_contact_type;
        $filter_contact_zipcode = $request->filter_contact_zipcode;
        $filter_religion = $request->filter_religion;
        $filter_faith_tradition = $request->filter_faith_tradition;
        $filter_denomination = $request->filter_denomination;
        $filter_judicatory_body = $request->filter_judicatory_body;
        $filter_email = $request->filter_email;
        $filter_phone = $request->filter_phone;
        $filter_tag = $request->filter_tag;
        $filter_map = $request->filter_map;

        $contacts = Contact::orderBy('contact_recordid', 'DESC');
        if ($search_term) {
            $contacts = $contacts
                ->where('contact_first_name', 'LIKE', '%' . $search_term . '%')
                ->orWhere('contact_middle_name', 'LIKE', '%' . $search_term . '%')
                ->orWhere('contact_last_name', 'LIKE', '%' . $search_term . '%')
                ->orWhere('contact_type', 'LIKE', '%' . $search_term . '%')
                ->orWhere('contact_religious_title', 'LIKE', '%' . $search_term . '%')
                ->orWhere('contact_title', 'LIKE', '%' . $search_term . '%')
                ->orWhere('contact_languages_spoken', 'LIKE', '%' . $search_term . '%')
                ->orWhere('contact_other_languages', 'LIKE', '%' . $search_term . '%')
                ->orWhere('contact_pronouns', 'LIKE', '%' . $search_term . '%')
                ->orWhere('contact_cell_phones', 'LIKE', '%' . $search_term . '%')
                ->orWhere('contact_personal_email', 'LIKE', '%' . $search_term . '%')
                ->orWhere('contact_email', 'LIKE', '%' . $search_term . '%');
        }

        $filter_contact_borough_list = [];
        $filter_contact_zipcode_list = [];
        $filter_contact_address_list = [];
        $filter_contact_languages_list = [];
        $filter_contact_type_list = [];
        $filter_contact_zipcode_list = [];
        $filter_religion_list = [];
        $filter_faith_tradition_list = [];
        $filter_denomination_list = [];
        $filter_judicatory_body_list = [];
        $filter_map_list = [];

        if ($filter_contact_borough) {
            $filter_contact_borough_list = explode('|', $filter_contact_borough);
        }
        if ($filter_contact_zipcode) {
            $filter_contact_zipcode_list = explode('|', $filter_contact_zipcode);
        }
        if ($filter_contact_languages) {
            $filter_contact_languages_list = explode('|', $filter_contact_languages);
        }
        if ($filter_contact_address) {
            $filter_contact_address_list = explode('|', $filter_contact_address);
        }
        if ($filter_contact_type) {
            $filter_contact_type_list = explode('|', $filter_contact_type);
        }
        if ($filter_contact_zipcode) {
            $filter_contact_zipcode_list = explode('|', $filter_contact_zipcode);
        }
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
        if ($filter_email) {
            if ($filter_email == 'Yes') {
                $contacts = $contacts->whereNotNull('contact_personal_email');
            }
            if ($filter_email == 'No') {
                $contacts = $contacts->whereNull('contact_personal_email');
            }
        }
        if ($filter_phone) {
            if ($filter_phone == 'Yes') {
                $contacts = $contacts->whereNotNull('contact_cell_phones');
            }
            if ($filter_phone == 'No') {
                $contacts = $contacts->whereNull('contact_cell_phones');
            }
        }
        if ($filter_map) {
            $filter_map_list = json_decode($filter_map);
        }

        //===========================================================================================
        //======================================= Filter Action =====================================

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

        if ($filter_contact_zipcode_list || $filter_contact_borough_list || $filter_contact_address_list || $filtered_location_recordid_list) {
            $query = Address::orderBy('address_recordid');
            if ($filter_contact_zipcode_list) {
                $query = $query->whereIn('address_zip_code', $filter_contact_zipcode_list);
            }
            if ($filter_contact_borough_list) {
                $query = $query->whereIn('address_city', $filter_contact_borough_list);
            }
            if ($filter_contact_address_list) {
                $query = $query->whereIn('address_1', $filter_contact_address_list);
            }
            if ($filtered_location_recordid_list) {
                $query = $query->whereIn('address_locations', $filtered_location_recordid_list);
            }

            $filtered_address_ids = $query->pluck('address_recordid')->toArray();
            $contacts = $contacts->whereIn('contact_mailing_address', $filtered_address_ids);
        }

        if ($filter_religion_list || $filter_faith_tradition_list || $filter_denomination_list || $filter_judicatory_body_list) {
            $query = Organization::orderBy('organization_recordid');
            if ($filter_religion_list) {
                $query = $query->whereIn('organization_religion', $filter_religion_list);
            }
            if ($filter_faith_tradition_list) {
                $query = $query->whereIn('organization_faith_tradition', $filter_faith_tradition_list);
            }
            if ($filter_denomination_list) {
                $query = $query->whereIn('organization_denomination', $filter_denomination_list);
            }
            if ($filter_judicatory_body_list) {
                $query = $query->whereIn('organization_judicatory_body', $filter_judicatory_body_list);
            }
            $filtered_organization_ids = $query->pluck('organization_recordid')->toArray();
            $contacts = $contacts->whereIn('contact_organizations', $filtered_organization_ids);
        }

        // if ($filter_contact_languages_list) {
        //     $contacts = $contacts->whereIn('contact_languages_spoken', $filter_contact_languages_list);
        // }
        if ($filter_contact_type_list) {
            $contacts = $contacts->whereIn('contact_type', $filter_contact_type_list);
        }
        if ($filter_contact_languages_list) {
            $contacts = $contacts->whereIn('contact_languages_spoken', $filter_contact_languages_list);
        }
        if ($filter_tag) {
            $contacts = $contacts->where('contact_tag', 'LIKE', '%' . $filter_tag . '%');
        }

        if ($filter_religion_list || $filter_faith_tradition_list || $filter_denomination_list || $filter_judicatory_body_list || $filter_contact_zipcode_list || $filter_contact_borough_list || $filter_contact_address_list || $filter_contact_languages_list || $filter_contact_type_list || $filter_contact_languages_list || $filtered_location_recordid_list) {

            $filtered_locations_list = new Collection();
            $filtered_all_contacts = $contacts->get();
            foreach ($filtered_all_contacts as $key => $value) {
                $filtered_locations = $value->address->locations;
                $filtered_locations_list = $filtered_locations_list->merge($filtered_locations);
            }
        } else {
            $filtered_locations_list = Location::with('services', 'address', 'phones')->distinct()->get();
        }

        $filtered_count = $contacts->count();
        $contacts = $contacts->offset($start)->limit($length)->get();
        $total_count = Contact::count();
        $result = [];
        $contact_info = [];
        $allLanguage = AllLanguage::get();
        foreach ($contacts as $contact) {
            $contact_info[0] = $contact->contact_recordid;
            $contact_info[1] = '';
            $contact_info[2] = '';
            $contact_info[3] = $contact->contact_recordid;
            $contact_info[4] = $contact->contact_first_name;
            $contact_info[5] = $contact->contact_middle_name;
            $contact_info[6] = $contact->contact_last_name;
            $contact_info[7] = '';
            $contact_info[8] = $contact->type != null ? $contact->type->contact_type : '';
            $contact_info[9] = $contact->contact_religious_title;
            $contact_info[10] = str_limit($contact->contact_title, 15, '...');
            $languageData = $contact->contact_languages_spoken != null ? explode(',', $contact->contact_languages_spoken) : [];
            $languageName = [];
            foreach ($allLanguage as $key => $value) {
                foreach ($languageData as $key1 => $value1) {
                    if ($value->id == (int) $value1) {
                        array_push($languageName, '<span class="badge badge-primary">' . $value->language_name . '</span>');
                    }
                }
            }
            $contact_info[11] = count($languageName) > 0 ? implode(' ', $languageName) : '';
            $contact_info[12] = $contact->contact_other_languages;
            $contact_info[13] = $contact->contact_pronouns;

            $contact_full_address_info = '';
            if ($contact->address['address_1'] != '') {
                $contact_full_address_info = $contact_full_address_info . $contact->address['address_1'];
            }

            if ($contact->address['address_city'] != '') {
                $contact_full_address_info = $contact_full_address_info . ', ' . $contact->address['address_city'];
            }

            if ($contact->address['address_state'] != '') {
                $contact_full_address_info = $contact_full_address_info . ', ' . $contact->address['address_state'];
            }

            if ($contact->address['address_zip_code'] != '') {
                $contact_full_address_info = $contact_full_address_info . ', ' . $contact->address['address_zip_code'];
            }

            $contact_info[14] = $contact_full_address_info;

            $contact_info[15] = $contact->contact_cell_phones;
            $contact_info[16] = $contact->cellphone['phone_number'];
            $contact_info[17] = $contact->emergencyphone['phone_number'];
            $contact_info[18] = $contact->officephone['phone_number'];
            $contact_info[19] = $contact->contact_personal_email;
            $contact_info[20] = $contact->contact_email;
            $contact_info[21] = $contact->organization['organization_religion'];
            $contact_info[22] = $contact->organization['organization_faith_tradition'];
            $contact_info[23] = $contact->organization['organization_denomination'];
            $contact_info[24] = $contact->organization['organization_judicatory_body'];
            $contact_info[25] = $contact->address['address_1'];
            $contact_info[26] = $contact->address['address_city'];
            $contact_info[27] = $contact->address['address_zip_code'];
            $contact_info[28] = $contact->organization['organization_recordid'];
            $contact_info[29] = $contact->organization['organization_name'] != null ? $contact->organization['organization_name'] : '';
            $contact_info[30] = $contact->contact_tag;

            array_push($result, $contact_info);
        }
        return response()->json(array('data' => $result, 'recordsTotal' => $total_count, 'recordsFiltered' => $filtered_count, 'filtered_locations_list' => $filtered_locations_list));
    }

    public function contacts(Request $request)
    {
        // $contacts = Contact::orderBy('contact_recordid', 'DESC')
        //     ->paginate(200);

        $address_addresses = Address::select("address_1")->distinct()->get();
        $address_cities = Address::select("address_city")->distinct()->get();
        $address_zipcodes = Address::select("address_zip_code")->distinct()->get();

        // $contact_types = Contact::select("contact_type")->distinct()->get();
        $contact_types = contactType::pluck('contact_type', 'contact_type');

        // $contact_languages = Contact::select("contact_languages_spoken")->distinct()->get();
        $contact_languages = AllLanguage::pluck('language_name', 'language_name');

        $contact_addresses = Contact::select("contact_mailing_address")->distinct()->get();
        // $organization_religions = Organization::select("organization_religion")->distinct()->get();
        $organization_religions = Religion::where('type', 'religion')->pluck('name', 'name');
        // $organization_faith_traditions = Organization::select("organization_faith_tradition")->distinct()->get();
        $faith_tradition_list = Religion::where('type', 'faith_tradition')->pluck('name', 'name');

        // $organization_denominations = Organization::select("organization_denomination")->distinct()->get();
        $denomination_list = Religion::where('type', 'denominations')->pluck('name', 'name');

        // $organization_judicatory_bodys = Organization::select("organization_judicatory_body")->distinct()->get();
        $organization_judicatory_bodys = Religion::where('type', 'judicatory_body')->pluck('name', 'name');

        $organization_boroughs = Organization::select("organization_borough")->distinct()->get();
        $organization_zipcodes = Organization::select("organization_zipcode")->distinct()->get();
        $contact_tags = Contact::select("contact_tag")->distinct()->get();
        $locations = Location::with('services', 'address', 'phones')->distinct()->get();

        $tag_list = [];
        foreach ($contact_tags as $key => $value) {
            $tags = explode(", ", trim($value->contact_tag));
            $tag_list = array_merge($tag_list, $tags);
        }
        $tag_list = array_unique($tag_list);

        $address_address_list = [];
        foreach ($address_addresses as $key => $value) {
            $addresses = explode(", ", trim($value->address_1));
            $address_address_list = array_merge($address_address_list, $addresses);
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

        // $faith_tradition_list = [];
        // foreach ($organization_faith_traditions as $key => $value) {
        //     $faith_traditions = explode(", ", trim($value->organization_faith_tradition));
        //     $faith_tradition_list = array_merge($faith_tradition_list, $faith_traditions);
        // }
        // $faith_tradition_list = array_unique($faith_tradition_list);

        // $denomination_list = [];
        // foreach ($organization_denominations as $key => $value) {
        //     $denominations = explode(", ", trim($value->organization_denomination));
        //     $denomination_list = array_merge($denomination_list, $denominations);
        // }
        // $denomination_list = array_unique($denomination_list);

        $map = Map::find(1);

        return view('frontEnd.contacts', compact('contact_types', 'contact_languages', 'contact_addresses', 'organization_religions', 'organization_judicatory_bodys'
            , 'organization_boroughs', 'organization_zipcodes', 'address_address_list', 'address_city_list', 'address_zipcode_list',
            'map', 'locations', 'faith_tradition_list', 'denomination_list', 'tag_list'));
    }

    public function group_operation(Request $request)
    {
        $checked_terms_list = explode(',', $request->input('checked_terms'));
        $checked_terms = '';
        if (empty($checked_terms_list)) {
            $checked_terms = '';
        } else {
            foreach ($checked_terms_list as $key => $value) {
                if ($checked_terms != '') {
                    $checked_terms = $checked_terms . ', ' . $value;
                } else {
                    $checked_terms = $value;
                }
            }
        }

        switch ($request->input('btn_submit')) {
            case 'save-to-filter-dynamic-group':
                $map = Map::find(1);
                $groups = Group::where('group_type', '=', 'Dynamic')->distinct()->get();
                $group_names = Group::where('group_type', '=', 'Dynamic')->select("group_name")->distinct()->get();

                // this section only for display religion name
                $religions = Religion::select('*');

                $religion_name = $request->input('religion') != null ? $religions->whereId($request->input('religion'))->select('name')->first()->name : '';
                $faith_tradition_name = $request->input('faith_tradition') != null ? $religions->whereId($request->input('faith_tradition'))->select('name')->first()->name : '';
                $denomination_name = $request->input('denomination') != null ? $religions->whereId($request->input('denomination'))->select('name')->first()->name : '';
                $judicatory_body_name = $request->input('judicatory_body') != null ? $religions->whereId($request->input('judicatory_body'))->select('name')->first()->name : '';

                $religion_filter = $request->input('religion');
                $faith_tradition_filter = $request->input('faith_tradition');
                $denomination_filter = $request->input('denomination');
                $judicatory_body_filter = $request->input('judicatory_body');
                $email_filter = $request->input('email');
                $phone_filter = $request->input('phone');
                $contact_address_filter = $request->input('contact_address');
                $contact_type_filter = $request->input('contact_type');
                $contact_languages_filter = $request->input('contact_languages');
                $contact_borough_filter = $request->input('contact_borough');
                $contact_zipcode_filter = $request->input('contact_zipcode');

                $filters = (object) [];

                $filters->religion_name = $religion_name;
                $filters->faith_tradition_name = $faith_tradition_name;
                $filters->denomination_name = $denomination_name;
                $filters->judicatory_body_name = $judicatory_body_name;

                $filters->religion_filter = $religion_filter;
                $filters->faith_tradition_filter = $faith_tradition_filter;
                $filters->denomination_filter = $denomination_filter;
                $filters->judicatory_body_filter = $judicatory_body_filter;
                $filters->email_filter = $email_filter;
                $filters->phone_filter = $phone_filter;
                $filters->contact_address_filter = $contact_address_filter;
                $filters->contact_type_filter = $contact_type_filter;
                $filters->contact_languages_filter = $contact_languages_filter;
                $filters->contact_borough_filter = $contact_borough_filter;
                $filters->contact_zipcode_filter = $contact_zipcode_filter;

                $filters_json = json_encode($filters);

                return view('frontEnd.contacts-add-dynamic-group', compact('group_names', 'groups', 'map', 'checked_terms', 'filters_json'));
                break;

            case 'add-to-new-static-group-btn':
                $map = Map::find(1);
                return view('frontEnd.contacts-add-new-static-group', compact('map', 'checked_terms'));
                break;

            case 'add-to-existing-static-group-btn':
                $map = Map::find(1);
                $groups = Group::where('group_type', '=', 'Static')->distinct()->get();
                $group_names = Group::where('group_type', '=', 'Static')->select("group_name")->distinct()->get();
                return view('frontEnd.contacts-add-static-group', compact('group_names', 'groups', 'map', 'checked_terms'));
                break;

            case 'download_csv':
                $filter_religion = $request->input('religion_list');
                $filter_faith_tradition = $request->input('faith_tradition_list');
                $filter_denomination = $request->input('denomination_list');
                $filter_judicatory_body = $request->input('judicatory_body_list');
                $filter_email = $request->input('email');
                $filter_phone = $request->input('phone');
                $filter_contact_address = $request->input('contact_address_list');
                $filter_contact_type = $request->input('contact_type_list');
                $filter_contact_languages = $request->input('contact_languages_list');
                $filter_contact_borough = $request->input('contact_borough_list');
                $filter_contact_zipcode = $request->input('contact_zipcode_list');

                $contacts = Contact::orderBy('contact_recordid', 'DESC');

                $filter_contact_borough_list = [];
                $filter_contact_zipcode_list = [];
                $filter_contact_address_list = [];
                $filter_contact_languages_list = [];
                $filter_contact_type_list = [];
                $filter_contact_zipcode_list = [];
                $filter_religion_list = [];
                $filter_faith_tradition_list = [];
                $filter_denomination_list = [];
                $filter_judicatory_body_list = [];

                if ($filter_contact_borough) {
                    $filter_contact_borough_list = explode(',', $filter_contact_borough);
                }
                if ($filter_contact_zipcode) {
                    $filter_contact_zipcode_list = explode(',', $filter_contact_zipcode);
                }
                if ($filter_contact_languages) {
                    $filter_contact_languages_list = explode(',', $filter_contact_languages);
                }
                if ($filter_contact_address) {
                    $filter_contact_address_list = explode(',', $filter_contact_address);
                }
                if ($filter_contact_type) {
                    $filter_contact_type_list = explode(',', $filter_contact_type);
                }
                if ($filter_contact_zipcode) {
                    $filter_contact_zipcode_list = explode(',', $filter_contact_zipcode);
                }
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
                if ($filter_email) {
                    if ($filter_email == 'Yes') {
                        $contacts = $contacts->whereNotNull('contact_personal_email');
                    }
                    if ($filter_email == 'No') {
                        $contacts = $contacts->whereNull('contact_personal_email');
                    }
                }
                if ($filter_phone) {
                    if ($filter_phone == 'Yes') {
                        $contacts = $contacts->whereNotNull('contact_cell_phones');
                    }
                    if ($filter_phone == 'No') {
                        $contacts = $contacts->whereNull('contact_cell_phones');
                    }
                }
                if ($filter_contact_zipcode_list || $filter_contact_borough_list || $filter_contact_address_list) {
                    $query = Address::orderBy('address_recordid');
                    if ($filter_contact_zipcode_list) {
                        $query = $query->whereIn('address_zip_code', $filter_contact_zipcode_list);
                    }
                    if ($filter_contact_borough_list) {
                        $query = $query->whereIn('address_city', $filter_contact_borough_list);
                    }
                    if ($filter_contact_address_list) {
                        $query = $query->whereIn('address_1', $filter_contact_address_list);
                    }
                    if ($filtered_location_recordid_list) {
                        $query = $query->whereIn('address_locations', $filtered_location_recordid_list);
                    }

                    $filtered_address_ids = $query->pluck('address_recordid')->toArray();
                    $contacts = $contacts->whereIn('contact_mailing_address', $filtered_address_ids);
                }
                if ($filter_religion_list || $filter_faith_tradition_list || $filter_denomination_list || $filter_judicatory_body_list) {
                    $query = Organization::orderBy('organization_recordid');
                    if ($filter_religion_list) {
                        $query = $query->whereIn('organization_religion', $filter_religion_list);
                    }
                    if ($filter_faith_tradition_list) {
                        $query = $query->whereIn('organization_faith_tradition', $filter_faith_tradition_list);
                    }
                    if ($filter_denomination_list) {
                        $query = $query->whereIn('organization_denomination', $filter_denomination_list);
                    }
                    if ($filter_judicatory_body_list) {
                        $query = $query->whereIn('organization_judicatory_body', $filter_judicatory_body_list);
                    }
                    $filtered_organization_ids = $query->pluck('organization_recordid')->toArray();
                    $contacts = $contacts->whereIn('contact_organizations', $filtered_organization_ids);
                }
                if ($filter_contact_languages_list) {
                    $contacts = $contacts->whereIn('contact_languages_spoken', $filter_contact_languages_list);
                }
                if ($filter_contact_type_list) {
                    $contacts = $contacts->whereIn('contact_type', $filter_contact_type_list);
                }
                if ($filter_contact_languages_list) {
                    $contacts = $contacts->whereIn('contact_languages_spoken', $filter_contact_languages_list);
                }
                $filtered_count = $contacts->count();

                $contactData = $contacts->get();
                $AllContacts = [];
                $allLanguage = AllLanguage::get();
                foreach ($contactData as $key => $value) {
                    $languageId = $value->contact_languages_spoken != '' ? explode(',',$value->contact_languages_spoken) : [];
                    $tempLanguage = [];
                    foreach ($languageId as $key1 => $id) {
                        foreach ($allLanguage as $key2 => $language) {
                            if($language->id == (int)$id){
                                array_push($tempLanguage,$language->language_name);
                            }
                        }
                    }

                    $value->contact_languages_spoken = count($tempLanguage) > 0 ? implode(',',$tempLanguage) : '';
                }
                
                $csvExporter = new \Laracsv\Export();
                
                $csv = CSV::find(1);
                $layout = Layout::find(1);
                $source = $layout->footer_csv;
                $csv->description = $source;
                $csv->save();

                $csv = CSV::all();

                return $csvExporter->build($contactData, [
                    'contact_first_name' => 'First Name', 'contact_middle_name' => 'Middle Name',
                    'contact_last_name' => 'Last Name', 'contact_languages_spoken' => 'Languages',
                    'contact_other_languages' => 'Other Languages', 'contact_religious_title' => 'Religious Title',
                    'contact_pronouns' => 'Pronouns', 'contact_personal_email' => 'Personal Email'])
                    ->build($csv, ['description' => ''])
                    ->download();

                break;

            case null:
                $filter_religion = $request->input('religion_list');
                $filter_faith_tradition = $request->input('faith_tradition_list');
                $filter_denomination = $request->input('denomination_list');
                $filter_judicatory_body = $request->input('judicatory_body_list');
                $filter_email = $request->input('email');
                $filter_phone = $request->input('phone');
                $filter_contact_address = $request->input('contact_address_list');
                $filter_contact_type = $request->input('contact_type_list');
                $filter_contact_languages = $request->input('contact_languages_list');
                $filter_contact_borough = $request->input('contact_borough_list');
                $filter_contact_zipcode = $request->input('contact_zipcode_list');

                $contact_map_image = $request->input('contact_map_image');

                $contacts = Contact::orderBy('contact_recordid', 'DESC');

                $filter_contact_borough_list = [];
                $filter_contact_zipcode_list = [];
                $filter_contact_address_list = [];
                $filter_contact_languages_list = [];
                $filter_contact_type_list = [];
                $filter_contact_zipcode_list = [];
                $filter_religion_list = [];
                $filter_faith_tradition_list = [];
                $filter_denomination_list = [];
                $filter_judicatory_body_list = [];

                if ($filter_contact_borough) {
                    $filter_contact_borough_list = explode(',', $filter_contact_borough);
                }
                if ($filter_contact_zipcode) {
                    $filter_contact_zipcode_list = explode(',', $filter_contact_zipcode);
                }
                if ($filter_contact_languages) {
                    $filter_contact_languages_list = explode(',', $filter_contact_languages);
                }
                if ($filter_contact_address) {
                    $filter_contact_address_list = explode(',', $filter_contact_address);
                }
                if ($filter_contact_type) {
                    $filter_contact_type_list = explode(',', $filter_contact_type);
                }
                if ($filter_contact_zipcode) {
                    $filter_contact_zipcode_list = explode(',', $filter_contact_zipcode);
                }
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
                if ($filter_email) {
                    if ($filter_email == 'Yes') {
                        $contacts = $contacts->whereNotNull('contact_personal_email');
                    }
                    if ($filter_email == 'No') {
                        $contacts = $contacts->whereNull('contact_personal_email');
                    }
                }
                if ($filter_phone) {
                    if ($filter_phone == 'Yes') {
                        $contacts = $contacts->whereNotNull('contact_cell_phones');
                    }
                    if ($filter_phone == 'No') {
                        $contacts = $contacts->whereNull('contact_cell_phones');
                    }
                }
                if ($filter_contact_zipcode_list || $filter_contact_borough_list || $filter_contact_address_list) {
                    $query = Address::orderBy('address_recordid');
                    if ($filter_contact_zipcode_list) {
                        $query = $query->whereIn('address_zip_code', $filter_contact_zipcode_list);
                    }
                    if ($filter_contact_borough_list) {
                        $query = $query->whereIn('address_city', $filter_contact_borough_list);
                    }
                    if ($filter_contact_address_list) {
                        $query = $query->whereIn('address_1', $filter_contact_address_list);
                    }
                    if ($filtered_location_recordid_list) {
                        $query = $query->whereIn('address_locations', $filtered_location_recordid_list);
                    }

                    $filtered_address_ids = $query->pluck('address_recordid')->toArray();
                    $contacts = $contacts->whereIn('contact_mailing_address', $filtered_address_ids);
                }
                if ($filter_religion_list || $filter_faith_tradition_list || $filter_denomination_list || $filter_judicatory_body_list) {
                    $query = Organization::orderBy('organization_recordid');
                    if ($filter_religion_list) {
                        $query = $query->whereIn('organization_religion', $filter_religion_list);
                    }
                    if ($filter_faith_tradition_list) {
                        $query = $query->whereIn('organization_faith_tradition', $filter_faith_tradition_list);
                    }
                    if ($filter_denomination_list) {
                        $query = $query->whereIn('organization_denomination', $filter_denomination_list);
                    }
                    if ($filter_judicatory_body_list) {
                        $query = $query->whereIn('organization_judicatory_body', $filter_judicatory_body_list);
                    }
                    $filtered_organization_ids = $query->pluck('organization_recordid')->toArray();
                    $contacts = $contacts->whereIn('contact_organizations', $filtered_organization_ids);
                }
                if ($filter_contact_languages_list) {
                    $contacts = $contacts->whereIn('contact_languages_spoken', $filter_contact_languages_list);
                }
                if ($filter_contact_type_list) {
                    $contacts = $contacts->whereIn('contact_type', $filter_contact_type_list);
                }
                if ($filter_contact_languages_list) {
                    $contacts = $contacts->whereIn('contact_languages_spoken', $filter_contact_languages_list);
                }
                $contacts = $contacts->get();
                
                $layout = Layout::find(1);
                set_time_limit(0);
                $pdf = PDF::loadView('frontEnd.contacts_download', compact('contacts', 'layout', 'contact_map_image'));
                return $pdf->download('contacts.pdf');

                break;
        }
    }

    public function add_group($id)
    {
        $map = Map::find(1);
        $contact = Contact::where('contact_recordid', '=', $id)->first();
        $groups = Group::where('group_type', '=', 'Static')->distinct()->get();
        $group_names = Group::where('group_type', '=', 'Static')->select("group_name")->distinct()->get();
        return view('frontEnd.contact-add-group', compact('contact', 'group_names', 'groups', 'map'));
    }

    public function create_new_static_group_add_members(Request $request)
    {
        $map = Map::find(1);
        $group_name = $request->group_name;
        $group_email = $request->group_email;
        $checked_contact_terms = $request->checked_contact_terms;

        $group_recordids = Group::select("group_recordid")->distinct()->get();
        $group_recordid_list = array();
        foreach ($group_recordids as $key => $value) {
            $group_recordid = $value->group_recordid;
            array_push($group_recordid_list, $group_recordid);
        }
        $group_recordid_list = array_unique($group_recordid_list);

        $group = new Group;
        $new_recordid = Group::max('group_recordid') + 1;
        if (in_array($new_recordid, $group_recordid_list)) {
            $new_recordid = Group::max('group_recordid') + 1;
        }
        $group->group_recordid = $new_recordid;

        $checked_contact_list = [];

        if ($checked_contact_terms != '') {
            $checked_contact_list = explode(", ", $checked_contact_terms);
            foreach ($checked_contact_list as $key => $id) {
                $contact = Contact::find($id);
                $contact->contact_group = $group->group_recordid;
                $contact->save();
            }

            $group_contact_list = Contact::where('contact_group', 'LIKE', '%' . $group->group_recordid . '%')->get();
            $group->group_members = count($group_contact_list);
        } else {
            $group->group_members = 0;
        }

        $group->group_name = $group_name;
        $group->group_emails = $group_email;
        $group->group_type = "Static";
        $group->group_last_modified = date("Y-m-d h:i:sa");
        $group->save();

        return redirect('groups');
    }

    public function contacts_update_static_group(Request $request)
    {
        $map = Map::find(1);
        $contact_group_name = $request->contact_group_name;
        $checked_contact_terms = $request->checked_contact_terms;

        $checked_contact_list = [];
        if ($checked_contact_terms != '') {
            $checked_contact_list = explode(", ", $checked_contact_terms);

            $group = Group::where('group_name', '=', $contact_group_name)->first();
            foreach ($checked_contact_list as $key => $id) {

                $contact = Contact::find($id);
                if ($contact) {
                    $contact->contact_group = $group->group_recordid;
                    $contact->save();
                }
            }

            $group_contact_list = Contact::where('contact_group', 'LIKE', '%' . $group->group_recordid . '%')->get();
            $group->group_members = count($group_contact_list);
            $group->group_last_modified = date("Y-m-d h:i:sa");
            $group->save();

            return redirect('groups');
        } else {
            return redirect('groups');
        }

    }

    public function contacts_update_dynamic_group(Request $request)
    {
        $map = Map::find(1);
        $contact_group_name = $request->contact_group_name;
        $checked_contact_terms = $request->checked_contact_terms;
        $filters_criteria = $request->filters_criteria;
        $filters = json_decode($filters_criteria);

        // $group = Group::where('group_name', '=', $contact_group_name)->first();
        $group = new Group;
        $group_name = $request->group_name;
        $group_email = $request->group_email;
        $checked_contact_terms = $request->checked_contact_terms;

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

        $checked_contact_list = [];
        if ($checked_contact_terms != '') {
            $checked_contact_list = explode(", ", $checked_contact_terms);

            foreach ($checked_contact_list as $key => $id) {
                $contact = Contact::find($id);
                // $contact->contact_group = $group->group_recordid;
                // $contact->save();
                $groupsValue = $contact->contact_group;
                $groupsArray = $groupsValue != null ? explode(',',$groupsValue) : [];
                array_push($groupsArray, $new_recordid);

                Contact::where('contact_recordid', $id)->update([
                    'contact_group' => implode(',', $groupsArray),
                ]);
                DB::commit();
            }
        }

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

        if ($religion_filter != null) {
            $contacts = $contacts->where('organizations.organization_religion', '=', $religion_filter);
        }
        if ($faith_tradition_filter != null) {
            $contacts = $contacts->where('organizations.organization_faith_tradition', '=', $faith_tradition_filter);
        }
        if ($denomination_filter != null) {
            $contacts = $contacts->where('organizations.organization_denomination', '=', $denomination_filter);
        }
        if ($judicatory_body_filter != null) {
            $contacts = $contacts->where('organizations.organization_judicatory_body', '=', $judicatory_body_filter);
        }
        if ($contact_address_filter != null) {
            $contacts = $contacts->where('address.address', '=', '%' . $contact_address_filter . '%');
        }
        if ($contact_zipcode_filter != null) {
            $contacts = $contacts->where('address.address', '=', '%' . $contact_zipcode_filter . '%');
        }
        if ($contact_borough_filter != null) {
            $contacts = $contacts->where('address.address', '=', '%' . $contact_borough_filter . '%');
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

        $group_contact_list = $contacts->get();

        $group->group_name = $group_name;
        $group->group_emails = $group_email;
        $group->group_type = "Dynamic";
        $group->group_created_at = date("Y-m-d h:i:sa");
        $group->group_filters = $filters_criteria;
        // $group->group_members = count($group_contact_list);
        $group->group_members = count($checked_contact_list);
        $group->group_last_modified = date("Y-m-d h:i:sa");

        $group->save();
        return redirect('groups');

    }

    public function add_comment(Request $request, $id)
    {

        $contact = Contact::find($id);
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
        $comment->comments_contact = $id;
        $comment->comments_datetime = $date_time;
        $comment->save();

        $comment_list = Comment::where('comments_contact', '=', $id)->get();

        return redirect('contacts/' . $id);

    }

    public function update_group(Request $request, $id, $group_name)
    {

        $map = Map::find(1);
        $contact_group_name = $group_name;

        $group = Group::where('group_name', '=', $contact_group_name)->first();

        $contact = Contact::find($id);
        if (!$contact->contact_group) {
            $contact->contact_group = $group->group_recordid;
        } else {

            if (strpos($contact->contact_group, strval($group->group_recordid)) === false) {
                $contact->contact_group = $contact->contact_group . ', ' . $group->group_recordid;
            }
        }
        $contact->save();

        $group_contact_list = Contact::where('contact_group', 'LIKE', '%' . $group->group_recordid . '%')->get();
        $group->group_members = count($group_contact_list);

        $group->group_last_modified = date("Y-m-d h:i:sa");
        $group->save();

        return redirect('contacts/' . $id);
    }

    public function contact($id)
    {
        $contact = Contact::where('contact_recordid', '=', $id)->first();
        $comment_list = Comment::where('comments_contact', '=', $id)->get();
        $locations = Location::with('services', 'address', 'phones')->where('location_contact', '=', $id)->get();
        if (count($locations) == 0) {
            $organization_recordid = $contact->contact_organizations;
            $organization_info = Organization::where('organization_recordid', '=', $organization_recordid)->first();
            $organization_location = '';
            if ($organization_info) {
                $organization_location = $organization_info->organization_locations;
            }
            $locations = Location::with('services', 'address', 'phones')->where('location_recordid', '=', $organization_location)->get();
        }

        $contact_mailing_address = Contact::where('contact_recordid', '=', $id)->select('contact_mailing_address')->first();
        $contact_mailing_address_id = $contact_mailing_address['contact_mailing_address'];
        $mailing_address_info = Address::where('address_recordid', '=', $contact_mailing_address_id)->select('address')->first();
        $mailing_address = $mailing_address_info['address'];

        $organization_id = $contact->contact_organizations;
        $organization_name_info = Organization::where('organization_recordid', '=', $organization_id)->select('organization_name')->first();
        $contact_organization_name = $organization_name_info["organization_name"];

        $contact_office_phone_id = $contact->contact_office_phones;
        $contact_office_phone_info = Phone::where('phone_recordid', '=', $contact_office_phone_id)->select('phone_number')->first();
        $office_phone_number = $contact_office_phone_info["phone_number"];

        $contact_cell_phone_id = $contact->contact_cell_phones;
        $contact_cell_phone_info = Phone::where('phone_recordid', '=', $contact_cell_phone_id)->select('phone_number')->first();
        $cell_phone_number = $contact_cell_phone_info["phone_number"];

        $contact_emergency_phone_id = $contact->contact_emergency_phones;
        $contact_emergency_phone_info = Phone::where('phone_recordid', '=', $contact_emergency_phone_id)->select('phone_number')->first();
        $emergency_phone_number = $contact_emergency_phone_info["phone_number"];

        $contact_office_fax_phone_id = $contact->contact_office_fax_phones;
        $contact_office_fax_phone_info = Phone::where('phone_recordid', '=', $contact_office_fax_phone_id)->select('phone_number')->first();
        $office_fax_phone_number = $contact_office_fax_phone_info["phone_number"];

        $groups = Group::where('group_type', '=', 'Static')->distinct()->get();
        $group_names = Group::where('group_type', '=', 'Static')->select("group_name")->distinct()->get();

        $contact_group_info = $contact->contact_group;
        $contact_group_recordid_list = explode(',', $contact_group_info);
        $contact_group_name_list = [];
        if ($contact->contact_group) {
            foreach ($contact_group_recordid_list as $key => $contact_group_recordid) {
                $contact_group = Group::where('group_recordid', '=', $contact_group_recordid)->first();
                if ($contact_group) {
                    $contact_group_name = $contact_group->group_name;
                    array_push($contact_group_name_list, $contact_group_name);
                }

            }
        }

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

        // this section for message table in contact
        $contacts = CampaignReport::where('contact_id', $contact->id)->where('status', 'Delivered')->get();

        return view('frontEnd.contact', compact('contact', 'locations', 'mailing_address', 'contact_organization_name', 'organization_id', 'comment_list',
            'office_phone_number', 'cell_phone_number', 'emergency_phone_number', 'office_fax_phone_number', 'groups', 'group_names', 'contact_group_name_list', 'contact_group_recordid_list',
            'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $map = Map::find(1);
        $organization_names = Organization::select("organization_name")->distinct()->get();
        $organization_types = Organization::select("organization_type")->distinct()->get();

        $organization_name_list = [];
        foreach ($organization_names as $key => $value) {
            $org_names = explode(", ", trim($value->organization_name));
            $organization_name_list = array_merge($organization_name_list, $org_names);
        }
        $organization_name_list = array_unique($organization_name_list);

        $contact_pronoun_list = ['He/Him', 'She/Her', 'They/Them'];

        $organization_type_list = [];
        foreach ($organization_types as $key => $value) {
            $org_types = explode(", ", trim($value->organization_type));
            $organization_type_list = array_merge($organization_type_list, $org_types);
        }
        $organization_type_list = array_unique($organization_type_list);

        // $contact_languages = ['English', 'Spanish', 'Hindi', 'Chinese', 'Arabic', 'Malay', 'German', 'Greek',
        //     'Thai', 'French', 'Korean', 'Japanese', 'Italian', 'Cartonese', 'Portuguese', 'Bengali',
        //     'Russian', 'Lahnda', 'Turkish', 'Tamil', 'Vietnamese', 'VIETNAMESE', 'Urdu'];

        $contact_languages = AllLanguage::pluck('language_name', 'id');

        $contact_type = contactType::pluck('contact_type', 'id');

        return view('frontEnd.contact-create', compact('map', 'organization_name_list', 'contact_pronoun_list',
            'organization_type_list', 'contact_languages', 'contact_type'));
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
        $contact = Contact::where('contact_recordid', '=', $id)->first();
        $comment_list = Comment::where('comments_contact', '=', $id)->get();
        $locations = Location::with('services', 'address', 'phones')->where('location_contact', '=', $id)->get();

        if (count($locations) == 0) {
            $organization_recordid = $contact->contact_organizations;
            $organization_info = Organization::where('organization_recordid', '=', $organization_recordid)->first();
            $organization_location = '';
            if ($organization_info) {
                $organization_location = $organization_info->organization_locations;
            }
            $locations = Location::with('services', 'address', 'phones')->where('location_recordid', '=', $organization_location)->get();
        }

        $groups = Group::where('group_type', '=', 'Static')->distinct()->get();
        $group_names = Group::where('group_type', '=', 'Static')->select("group_name")->distinct()->get();

        $spokenLanguage = $contact->contact_languages_spoken != '' ? explode(',', $contact->contact_languages_spoken) : [];
        $languageName = [];
        foreach ($spokenLanguage as $key => $value) {
            $language = AllLanguage::whereId($value)->first();
            array_push($languageName, $language->language_name);
        }
        $contact_languages_spoken = implode(',', $languageName);

        $contact_group_info = $contact->contact_group;
        $groupData = explode(',', $contact_group_info);
        $contact_group_recordid_list = [];
        foreach ($groupData as $key => $value) {
            if ($value != '') {
                array_push($contact_group_recordid_list, $value);
            }
        }
        $contact_group_name_list = [];
        if ($contact->contact_group) {
            foreach ($contact_group_recordid_list as $key => $contact_group_recordid) {
                $contact_group = Group::where('group_recordid', '=', $contact_group_recordid)->first();
                if ($contact_group) {
                    $contact_group_name = $contact_group->group_name;
                    array_push($contact_group_name_list, $contact_group_name);
                }

            }
        }
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

// this section for message table in contact
        $contacts = CampaignReport::where('contact_id', $contact->id)->where('status', 'Delivered')->get();

        return view('frontEnd.contact', compact('contact', 'locations', 'comment_list', 'groups', 'group_names', 'contact_group_name_list', 'contact_group_recordid_list',
            'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'contacts', 'contact_languages_spoken'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contact = Contact::where('contact_recordid', '=', $id)->first();

        $organization_names = Organization::select("organization_name")->distinct()->get();
        $organization_types = Organization::select("organization_type")->distinct()->get();
        $organization_id = $contact->contact_organizations;
        $organization_name_info = Organization::where('organization_recordid', '=', $organization_id)->select('organization_name')->first();
        $contact_organization_name = $organization_name_info["organization_name"];

        // $contact_languages = ['English', 'Spanish', 'Hindi', 'Chinese', 'Arabic', 'Malay', 'German', 'Greek',
        //     'Thai', 'French', 'Korean', 'Japanese', 'Italian', 'Cartonese', 'Portuguese', 'Bengali',
        //     'Russian', 'Lahnda', 'Turkish', 'Tamil', 'Vietnamese', 'VIETNAMESE', 'Urdu'];
        $contact_languages = AllLanguage::pluck('language_name', 'id');

        // $contact_types = Contact::select("contact_type")->distinct()->get();
        $contact_types = contactType::pluck('contact_type', 'id');

        $organization_name_list = [];
        foreach ($organization_names as $key => $value) {
            $org_names = explode(", ", trim($value->organization_name));
            $organization_name_list = array_merge($organization_name_list, $org_names);
        }
        $organization_name_list = array_unique($organization_name_list);

        $contact_pronoun_list = ['He/Him', 'She/Her', 'They/Them'];

        $organization_type_list = [];
        foreach ($organization_types as $key => $value) {
            $org_types = explode(", ", trim($value->organization_type));
            $organization_type_list = array_merge($organization_type_list, $org_types);
        }
        $organization_type_list = array_unique($organization_type_list);

        $contact_office_phone_id = $contact->contact_office_phones;
        $contact_office_phone_info = Phone::where('phone_recordid', '=', $contact_office_phone_id)->select('phone_number')->first();
        $office_phone_number = $contact_office_phone_info["phone_number"];

        $contact_cell_phone_id = $contact->contact_cell_phones;
        $contact_cell_phone_info = Phone::where('phone_recordid', '=', $contact_cell_phone_id)->select('phone_number')->first();
        $cell_phone_number = $contact_cell_phone_info["phone_number"];

        $contact_emergency_phone_id = $contact->contact_emergency_phones;
        $contact_emergency_phone_info = Phone::where('phone_recordid', '=', $contact_emergency_phone_id)->select('phone_number')->first();
        $emergency_phone_number = $contact_emergency_phone_info["phone_number"];

        $contact_office_fax_phone_id = $contact->contact_office_fax_phones;
        $contact_office_fax_phone_info = Phone::where('phone_recordid', '=', $contact_office_fax_phone_id)->select('phone_number')->first();
        $office_fax_phone_number = $contact_office_fax_phone_info["phone_number"];

        $contact_mailing_address = Contact::where('contact_recordid', '=', $id)->select('contact_mailing_address')->first();
        $contact_mailing_address_id = $contact_mailing_address['contact_mailing_address'];
        $mailing_address_info = Address::where('address_recordid', '=', $contact_mailing_address_id)->select('address')->first();
        $mailing_address = $mailing_address_info['address'];

        $map = Map::find(1);
        return view('frontEnd.contact-edit', compact('contact', 'contact_organization_name', 'contact_pronoun_list',
            'map', 'organization_name_list', 'organization_type_list', 'contact_languages',
            'office_phone_number', 'cell_phone_number', 'emergency_phone_number', 'office_fax_phone_number',
            'mailing_address', 'contact_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function tagging(Request $request, $id)
    {
        $contact = Contact::find($id);
        $contact->contact_tag = $request->tokenfield;
        $contact->save();
        return redirect('contacts/' . $id);
    }

    public function add_new_contact(Request $request)
    {
        $contact = new Contact;
        $phone_recordids = Phone::select("phone_recordid")->distinct()->get();
        $phone_recordid_list = array();
        foreach ($phone_recordids as $key => $value) {
            $phone_recordid = $value->phone_recordid;
            array_push($phone_recordid_list, $phone_recordid);
        }
        $phone_recordid_list = array_unique($phone_recordid_list);

        $address_recordids = Address::select("address_recordid")->distinct()->get();
        $address_recordid_list = array();
        foreach ($address_recordids as $key => $value) {
            $address_recordid = $value->address_recordid;
            array_push($address_recordid_list, $address_recordid);
        }
        $address_recordid_list = array_unique($address_recordid_list);

        $contact->contact_first_name = $request->contact_first_name;
        $contact->contact_middle_name = $request->contact_middle_name;
        $contact->contact_last_name = $request->contact_last_name;
        $contact->contact_type = $request->contact_type;
        $contact->contact_religious_title = $request->contact_religious_title;
        $contact->contact_pronouns = $request->contact_pronouns;
        $contact->contact_personal_email = $request->contact_personal_email;
        $contact->contact_email = $request->contact_email;
        $contact->contact_title = $request->contact_title;

        $contact_languages_spoken_list = $request->contact_languages_spoken;
        $contact_languages_spoken_info = '';
        if (!empty($contact_languages_spoken_list)) {
            $contact_languages_spoken_info = implode(',', $contact_languages_spoken_list);
        }
        $contact->contact_languages_spoken = $contact_languages_spoken_info;

        $contact->contact_other_languages = $request->contact_other_languages;
        $contact->flag = 'modified';

        $organization_name = $request->contact_organization_name;
        $contact_organization = Organization::where('organization_name', '=', $organization_name)->first();
        $contact_organization_id = $contact_organization["organization_recordid"];
        $contact->contact_organizations = $contact_organization_id;

        $contact_mailing_address = $request->contact_mailing_address;
        $address = Address::where('address', '=', $contact_mailing_address)->first();
        if ($address != null) {
            $address_id = $address["address_recordid"];
            $contact->contact_mailing_address = $address_id;
        } else {
            $address = new Address;
            $new_recordid = Address::max('address_recordid') + 1;
            if (in_array($new_recordid, $address_recordid_list)) {
                $new_recordid = Address::max('address_recordid') + 1;
            }
            $address->address_recordid = $new_recordid;
            $address->address = $contact_mailing_address;
            $address_reference_list = explode(",", $contact_mailing_address);
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
            $contact->contact_mailing_address = $address->address_recordid;
            $address->save();
        }

        $contact_cell_phones = $request->contact_cell_phones;
        $cell_phone = Phone::where('phone_number', '=', $contact_cell_phones)->first();
        if ($cell_phone != null) {
            $cell_phone_id = $cell_phone["phone_recordid"];
            $contact->contact_cell_phones = $cell_phone_id;
        } else {
            $phone = new Phone;
            $new_recordid = Phone::max('phone_recordid') + 1;
            if (in_array($new_recordid, $phone_recordid_list)) {
                $new_recordid = Phone::max('phone_recordid') + 1;
            }
            $phone->phone_recordid = $new_recordid;
            $phone->phone_number = $contact_cell_phones;
            $phone->phone_type = "cell phone";
            $contact->contact_cell_phones = $phone->phone_recordid;
            $phone->save();
        }

        $contact_office_phones = $request->contact_office_phones;

        $office_phone = Phone::where('phone_number', '=', $contact_office_phones)->first();
        if ($office_phone != null) {
            $office_phone_id = $office_phone["phone_recordid"];
            $contact->contact_office_phones = $office_phone_id;
        } else {
            $phone = new Phone;
            $new_recordid = Phone::max('phone_recordid') + 1;
            if (in_array($new_recordid, $phone_recordid_list)) {
                $new_recordid = Phone::max('phone_recordid') + 1;
            }
            $phone->phone_recordid = $new_recordid;
            $phone->phone_number = $contact_office_phones;
            $phone->phone_type = "office phone";
            $contact->contact_office_phones = $phone->phone_recordid;
            $phone->save();
        }

        $contact_emergency_phones = $request->contact_emergency_phones;
        $emergency_phone = Phone::where('phone_number', '=', $contact_emergency_phones)->first();
        if ($emergency_phone != null) {
            $emergency_phone_id = $emergency_phone["phone_recordid"];
            $contact->contact_emergency_phones = $emergency_phone_id;
        } else {
            $phone = new Phone;
            $new_recordid = Phone::max('phone_recordid') + 1;
            if (in_array($new_recordid, $phone_recordid_list)) {
                $new_recordid = Phone::max('phone_recordid') + 1;
            }
            $phone->phone_recordid = $new_recordid;
            $phone->phone_number = $contact_emergency_phones;
            $phone->phone_type = "emergency phone";
            $contact->contact_emergency_phones = $phone->phone_recordid;
            $phone->save();
        }

        $contact_office_fax_phones = $request->contact_office_fax_phones;
        $office_fax_phone = Phone::where('phone_number', '=', $contact_office_fax_phones)->first();
        if ($office_fax_phone != null) {
            $office_fax_phone_id = $office_fax_phone["phone_recordid"];
            $contact->contact_office_fax_phones = $office_fax_phone_id;
        } else {
            $phone = new Phone;
            $new_recordid = Phone::max('phone_recordid') + 1;
            if (in_array($new_recordid, $phone_recordid_list)) {
                $new_recordid = Phone::max('phone_recordid') + 1;
            }
            $phone->phone_recordid = $new_recordid;
            $phone->phone_number = $contact_office_fax_phones;
            // $phone->phone_type = "office fax";
            $contact->contact_office_fax_phones = $phone->phone_recordid;
            $phone->save();
        }

        $contact_recordids = Contact::select("contact_recordid")->distinct()->get();
        $contact_recordid_list = array();
        foreach ($contact_recordids as $key => $value) {
            $contact_recordid = $value->contact_recordid;
            array_push($contact_recordid_list, $contact_recordid);
        }
        $contact_recordid_list = array_unique($contact_recordid_list);

        $new_recordid = Contact::max('contact_recordid') + 1;
        if (in_array($new_recordid, $contact_recordid_list)) {
            $new_recordid = Contact::max('contact_recordid') + 1;
        }
        $contact->contact_recordid = $new_recordid;

        $contact->save();

        return redirect('contacts');
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);

        $phone_recordids = Phone::select("phone_recordid")->distinct()->get();
        $phone_recordid_list = array();
        foreach ($phone_recordids as $key => $value) {
            $phone_recordid = $value->phone_recordid;
            array_push($phone_recordid_list, $phone_recordid);
        }
        $phone_recordid_list = array_unique($phone_recordid_list);

        $address_recordids = Address::select("address_recordid")->distinct()->get();
        $address_recordid_list = array();
        foreach ($address_recordids as $key => $value) {
            $address_recordid = $value->address_recordid;
            array_push($address_recordid_list, $address_recordid);
        }
        $address_recordid_list = array_unique($address_recordid_list);

        $contact->contact_first_name = $request->contact_first_name;
        $contact->contact_middle_name = $request->contact_middle_name;
        $contact->contact_last_name = $request->contact_last_name;
        $contact->contact_type = $request->contact_type;
        $contact->contact_religious_title = $request->contact_religious_title;
        $contact->contact_pronouns = $request->contact_pronouns;
        $contact->contact_personal_email = $request->contact_personal_email;
        $contact->contact_email = $request->contact_email;

        $contact_languages_spoken_list = $request->contact_languages_spoken;
        $contact_languages_spoken_info = '';
        if (!empty($contact_languages_spoken_list)) {
            $contact_languages_spoken_info = implode(',', $contact_languages_spoken_list);
        }
        $contact->contact_languages_spoken = $contact_languages_spoken_info;

        $contact->contact_other_languages = $request->contact_other_languages;
        $contact->flag = 'modified';

        $organization_name = $request->contact_organization_name;
        $contact_organization = Organization::where('organization_name', '=', $organization_name)->first();
        $contact_organization_id = $contact_organization["organization_recordid"];
        $contact->contact_organizations = $contact_organization_id;

        $contact_mailing_address = $request->contact_mailing_address;
        $address = Address::where('address', '=', $contact_mailing_address)->first();
        if ($address != null) {
            $address_id = $address["address_recordid"];
            $contact->contact_mailing_address = $address_id;
        } else {
            $address = new Address;
            $new_recordid = Address::max('address_recordid') + 1;
            if (in_array($new_recordid, $address_recordid_list)) {
                $new_recordid = Address::max('address_recordid') + 1;
            }
            $address->address_recordid = $new_recordid;
            $address->address = $contact_mailing_address;
            $address_reference_list = explode(",", $contact_mailing_address);
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
            $contact->contact_mailing_address = $address->address_recordid;
            $address->save();
        }

        $contact_cell_phones = $request->contact_cell_phones;
        $cell_phone = Phone::where('phone_number', '=', $contact_cell_phones)->first();
        if ($cell_phone != null) {
            $cell_phone_id = $cell_phone["phone_recordid"];
            $contact->contact_cell_phones = $cell_phone_id;
        } else {
            $phone = new Phone;
            $new_recordid = Phone::max('phone_recordid') + 1;
            if (in_array($new_recordid, $phone_recordid_list)) {
                $new_recordid = Phone::max('phone_recordid') + 1;
            }
            $phone->phone_recordid = $new_recordid;
            $phone->phone_number = $contact_cell_phones;
            // $phone->phone_type = "cell phone";
            $contact->contact_cell_phones = $phone->phone_recordid;
            $phone->save();
        }
        if ($request->has('contact_office_phones')) {
            $contact_office_phones = $request->contact_office_phones;
            $office_phone = Phone::where('phone_number', '=', $contact_office_phones)->where('phone_type', 'office phone')->first();
            if ($office_phone != null) {
                $office_phone_id = $office_phone["phone_recordid"];
                $contact->contact_office_phones = $office_phone_id;
            } else {
                $phone = new Phone;
                $new_recordid = Phone::max('phone_recordid') + 1;
                if (in_array($new_recordid, $phone_recordid_list)) {
                    $new_recordid = Phone::max('phone_recordid') + 1;
                }
                $phone->phone_recordid = $new_recordid;
                $phone->phone_number = $contact_office_phones;
                $phone->phone_type = "office phone";
                $contact->contact_office_phones = $phone->phone_recordid;
                $phone->save();
            }

        }

        $contact_emergency_phones = $request->contact_emergency_phones;
        $emergency_phone = Phone::where('phone_number', '=', $contact_emergency_phones)->first();
        if ($emergency_phone != null) {
            $emergency_phone_id = $emergency_phone["phone_recordid"];
            $contact->contact_emergency_phones = $emergency_phone_id;
        } else {
            $phone = new Phone;
            $new_recordid = Phone::max('phone_recordid') + 1;
            if (in_array($new_recordid, $phone_recordid_list)) {
                $new_recordid = Phone::max('phone_recordid') + 1;
            }
            $phone->phone_recordid = $new_recordid;
            $phone->phone_number = $contact_emergency_phones;
            // $phone->phone_type = "emergency phone";
            $contact->contact_emergency_phones = $phone->phone_recordid;
            $phone->save();
        }

        $contact_office_fax_phones = $request->contact_office_fax_phones;
        $office_fax_phone = Phone::where('phone_number', '=', $contact_office_fax_phones)->first();
        if ($office_fax_phone != null) {
            $office_fax_phone_id = $office_fax_phone["phone_recordid"];
            $contact->contact_office_fax_phones = $office_fax_phone_id;
        } else {
            $phone = new Phone;
            $new_recordid = Phone::max('phone_recordid') + 1;
            if (in_array($new_recordid, $phone_recordid_list)) {
                $new_recordid = Phone::max('phone_recordid') + 1;
            }
            $phone->phone_recordid = $new_recordid;
            $phone->phone_number = $contact_office_fax_phones;
            // $phone->phone_type = "office fax";
            $contact->contact_office_fax_phones = $phone->phone_recordid;
            $phone->save();
        }

        $contact->save();

        return redirect('contacts/' . $id);
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

    public function delete_contact(Request $request)
    {
        $contact_recordid = $request->input('contact_recordid');
        $contact = Contact::where('contact_recordid', '=', $contact_recordid)->first();
        $contact->delete();

        return redirect('contacts');
    }
}
