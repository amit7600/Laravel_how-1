@extends('layouts.app')
@section('title')
Contacts
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


<style type="text/css">
.table a{
    text-decoration:none !important;
    color: rgba(40,53,147,.9);
    white-space: normal;
}
.footable.breakpoint > tbody > tr > td > span.footable-toggle{
    position: absolute;
    right: 25px;
    font-size: 25px;
    color: #000000;
}
.ui-menu .ui-menu-item .ui-state-active {
    padding-left: 0 !important;
}
ul#ui-id-1 {
    width: 260px !important;
}

#map{
    position: relative !important;
    z-index: 0 !important;
}
@media (max-width: 768px) {
    .property{
        padding-left: 30px !important;
    }
    #map{
        display: block !important;
        width: 100% !important;
    }
}

button[data-id="religion"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="faith_tradition"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="denomination"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="judicatory_body"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="has-email"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="has-phone"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="contact_type"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="contact_address"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="contact_languages"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="contact_borough"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="contact_zipcode"] {
    height: 100%;
    border: 1px solid #ddd;
}
.sel-label-org {
    width: 15%;
}
#clear-filter-contacts-btn {
    width: 100%;
}
#tbl-contact_wrapper {
    overflow-x: scroll;
}
</style>

@section('content')
<div class="wrapper">
    <!-- Page Content Holder -->
    <div id="contacts-content" class="container">
        <form action="/contacts/action_group" method="GET">
            <div class="row">
                <div class="col-sm-8 p-20">                        
                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4">Religion: </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="religion-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="religion" name="religion">
                            @foreach($organization_religions as $key => $organization_religion)
                                <option value="{{$organization_religion->organization_religion}}">{{$organization_religion->organization_religion}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4">Faith Tradition: </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="faith_tradition-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="faith_tradition" name="faith_tradition">
                            @foreach($faith_tradition_list as $key => $faith_tradition)
                                <option value="{{$faith_tradition}}">{{$faith_tradition}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4">Denomination: </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="denomination-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="denomination" name="denomination">
                            @foreach($denomination_list as $key => $denomination)
                                <option value="{{$denomination}}">{{$denomination}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4">Judicatory Body: </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="judicatory_body-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="judicatory_body" name="judicatory_body">
                            @foreach($organization_judicatory_bodys as $key => $organization_judicatory_body)
                                <option value="{{$organization_judicatory_body->organization_judicatory_body}}">{{$organization_judicatory_body->organization_judicatory_body}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4">Email: </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="email_body-div">
                            <select class="form-control" id="email" name="email">
                                <option value="All" checked>All</option>
                                <option value="Has Email">Has Email</option>
                                <option value="No Email">No Email</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4">Phone: </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="phone_body-div">
                            <select class="form-control" id="phone" name="phone">
                                <option value="All" checked>All</option>
                                <option value="Has Email">Has Phone</option>
                                <option value="No Email">No Phone</option>
                            </select>
                        </div>
                    </div>   
                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4">Address: </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="address-div">
                        <select class="form-control selectpicker"  multiple data-live-search="true" id="contact_address" name="contact_address">
                            @foreach($address_address_list as $key => $address_address)
                                <option value="{{$address_address}}">{{$address_address}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>             

                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4">Contact Type: </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="contact_type-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="contact_type" name="contact_type">
                            @foreach($contact_types as $key => $contact_type)
                                <option value="{{$contact_type->contact_type}}">{{$contact_type->contact_type}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4">Languages: </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="contact_languages-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="contact_languages" name="contact_languages">
                            @foreach($contact_languages as $key => $contact_language)
                                <option value="{{$contact_language->contact_languages_spoken}}">{{$contact_language->contact_languages_spoken}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4">Borough: </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="contact_borough-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="contact_borough" name="contact_borough">
                            @foreach($address_city_list as $key => $address_city)
                                <option value="{{$address_city}}">{{$address_city}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4">Zipcode: </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="contact_zipcode-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="contact_zipcode" name="contact_zipcode">
                            @foreach($address_zipcode_list as $key => $address_zipcode)
                                <option value="{{$address_zipcode}}">{{$address_zipcode}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>               
                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4"></label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="clear-btn-div">
                            <button class="btn btn-success btn-rounded" id="clear-filter-contacts-btn"><i class="fa fa-refresh"></i> Clear Filters</button>
                        </div>
                    </div>
                </div>       
                <div class="col-md-4 property">
                    <div class="card">
                        <div id="map" style="width:initial;margin-top: 0;height: 50vh;"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <input type="hidden" id="checked_terms" name="checked_terms">
                    <button type="submit" class="btn btn-secondary btn-rounded" name="btn_submit" value="save-to-filter-dynamic-group" id="save-to-filter-dynamic-group">Save Filter as Dynamic Group</button>
                    <button type="submit" class="btn btn-primary btn-rounded" name="btn_submit" value="add-to-new-static-group-btn" id="add-to-new-static-group-btn">Add to New Static Group</button>
                    <button type="submit" class="btn btn-danger btn-rounded" name="btn_submit" value="add-to-existing-static-group-btn" id="add-to-existing-static-group-btn">Add to Existing Static Group</button>
                </div> 
                <div class="col-sm-12 p-20"> 
                    <table class="table table-striped jambo_table bulk_action nowrap" id="tbl-contact">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="default-inactive">Id</th>
                                <th class="default-active">First Name</th>

                                <th class="default-active">Middle Name</th>
                                <th class="default-active">Last Name</th>
                                <th class="default-active">Organization</th>
                                <th class="default-active">Contact Type</th>
                                <th class="default-active">Religious Title</th>

                                <th class="default-active">Position Title</th> 
                                <th class="default-inactive">Languages Spoken</th>
                                <th class="default-inactive">Other Languages</th>       
                                <th class="default-inactive">Pronouns</th>
                                <th class="default-inactive">Mailing Address</th>   

                                <th class="default-inactive">Cell Phone</th>  
                                <th class="default-active">Office Phone</th>   
                                <th class="default-inactive">Emergency Phone</th>  
                                <th class="default-inactive">Office Fax</th>  
                                <th class="default-inactive">Personal Email</th>  

                                <th class="default-inactive">Work Email</th>
                                <th class="default-inactive">Religion</th>
                                <th class="default-inactive">Faith Traditional</th>
                                <th class="default-inactive">Denomination</th>
                                <th class="default-inactive">Judicatory Body</th>                          
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($contacts as $key => $contact)
                            <tr>
                                <td>
                                    {{$contact->contact_recordid}}
                                </td>
                                <td>
                                    <a class="btn btn-primary open-td" href="/contact/{{$contact->contact_recordid}}" style="color: white;">Open</a>
                                </td>
                                <td>
                                    <button class="btn btn-danger delete-td" value="{{$contact->contact_recordid}}" data-toggle="modal" data-target=".bs-delete-modal-lg"><i class="fa fa-fw fa-remove"></i>Delete</button>
                                </td>
                                <td>{{$contact->contact_recordid}}</td>
                                <td>{{$contact->contact_first_name}}</td>
                                <td>{{$contact->contact_middle_name}}</td>
                                <td>{{$contact->contact_last_name}}</td>
                                <td>
                                    <a id="contact_organization_link" style="color: #3949ab; text-decoration: underline;" href="/organization/{{$contact->organization['organization_recordid']}}">{{$contact->organization['organization_name']}}</a>
                                </td>
                                <td>{{$contact->contact_type}}</td>
                                <td>{{$contact->contact_religious_title}}</td>                            
                                <td>{{ str_limit($contact->contact_title, 15, '...') }}</td>

                                <td>{{$contact->contact_languages_spoken}}</td>
                                <td>{{$contact->contact_other_languages}}</td>
                                <td>{{$contact->contact_pronouns}}</td>                                 
                                <td>@if($contact->address['address_1'] != ''){{$contact->address['address_1']}}, @endif @if($contact->address['address_city'] != ''){{$contact->address['address_city']}}, @endif{{$contact->address['address_state']}}@if($contact->address['address_zip_code'] != ''), {{$contact->address['address_zip_code']}}@endif</td>
                                <td>{{$contact->contact_cell_phones}}</td>
                                <td>{{$contact->cellphone['phone_number']}}</td>
                                <td>{{$contact->emergencyphone['phone_number']}}</td>
                                <td>{{$contact->officephone['phone_number']}}</td>
                                <td>{{$contact->contact_personal_email}}</td>
                                <td>{{$contact->contact_email}}</td>
                                <td>{{$contact->organization['organization_religion']}}</td>
                                <td>{{$contact->organization['organization_faith_tradition']}}</td>
                                <td>{{$contact->organization['organization_denomination']}}</td>
                                <td>{{$contact->organization['organization_judicatory_body']}}</td>                            

                            </tr>
                        @endforeach
                        <tbody>
                    </table>
                </div>
            </div>
        </form>
        <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="/contact_delete_filter" method="POST" id="contact_delete_filter">
                        {!! Form::token() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Delete Contact</h4>
                        </div>
                        <div class="modal-body">
                            
                            <input type="hidden" id="contact_recordid" name="contact_recordid">
                            <h4>Are you sure to delete this contact?</h4>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger btn-delete">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('customScript')

<script type="text/javascript" src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>
<script src="{{asset('js/markerclusterer.js')}}"></script>
<script>
    var dataTable;
    var checked_terms_set;
    $(document).ready(function() {
        dataTable = $('#tbl-contact').DataTable({
            "scrollX": true,
            dom: 'lBfrtip',
            order: [[ 2, 'desc' ]],
            buttons: [{
                extend: 'colvis',
                columns: [11, 12, 13, 14, 19]
            }],
            columnDefs: [
                { 
                    targets: 'default-inactive', 
                    visible: false
                },
                {
                    'targets': 0,
                    'checkboxes': {
                        'selectRow': true
                    },
                }
            ],
            'select': {
                'style': 'multi'
            },
        });           
    })
    $('#add-to-existing-static-group-btn').click(function(e){
        if (!checked_terms_set) {
            e.preventDefault();
            var checked_terms = dataTable.column(0).checkboxes.selected();
            $('#checked_terms').val(checked_terms.join(","));
            checked_terms_set = true;
            $(this).trigger('click');
        }
    })

    $('select#contact_borough').on('change', function() {
        
        var selectedList = $(this).val();
        search = selectedList.join('|')
        dataTable
            .column(14)
            .search(search ? search : '', true, false).draw();
    });
    $('select#contact_zipcode').on('change', function() {
        
        var selectedList = $(this).val();
        search = selectedList.join('|')
        dataTable
            .column(14)
            .search(search ? search : '', true, false).draw();
    });
    $('select#contact_languages').on('change', function() {
        
        var selectedList = $(this).val();
        search = selectedList.join('|')
        dataTable
            .column(11)
            .search(search ? '^' + search + '$' : '', true, false).draw();
    });
    $('select#contact_address').on('change', function() {
        
        var selectedList = $(this).val();
        search = selectedList.join('|')
        dataTable
            .column(14)
            .search(search ? search : '', true, false).draw();
    });
    $('select#contact_type').on('change', function() {
        
        var selectedList = $(this).val();
        search = selectedList.join('|')
        dataTable
            .column(8)
            .search(search ? search : '', true, false).draw();
    });
    $('select#religion').on('change', function() {
        
        var selectedList = $(this).val();
        search = selectedList.join('|')
        dataTable
            .column(21)
            .search(search ? search : '', true, false).draw();
    });
    $('select#faith_tradition').on('change', function() {
        
        var selectedList = $(this).val();
        search = selectedList.join('|')
        search = search.replace(/\(/g, "\\(")
        search = search.replace(/\)/g, "\\)")
        dataTable
            .column(22)
            .search(search ? search : '', true, false).draw();
    });
    $('select#denomination').on('change', function() {
        
        var selectedList = $(this).val();
        search = selectedList.join('|')
        search = search.replace(/\(/g, "\\(")
        search = search.replace(/\)/g, "\\)")
        dataTable
            .column(23)
            .search(search ? search : '', true, false).draw();
    });
    $('select#judicatory_body').on('change', function() {
        
        var selectedList = $(this).val();
        search = selectedList.join('|')
        search = search.replace(/\(/g, "\\(")
        search = search.replace(/\)/g, "\\)")
        dataTable
            .column(24)
            .search(search ? '^' + search + '$' : '', true, false).draw();
    });
    $('select#email').on('change', function() {
        
        var selected = $(this).val();
        if (selected == 'Has Email') {
            var search = ".*\\S+.*";
            dataTable
                .column(20)
                .search(search, true, false).draw();
        } else if (selected == 'No Email') {
            var search = "^$";
            dataTable
                .column(20)
                .search(search, true, false).draw();
        } else {
            dataTable
                .column(20)
                .search('', true, false).draw();
        }
    });
    $('select#phone').on('change', function() {
        
        var selected = $(this).val();
        if (selected == 'Has Phone') {
            var search = ".*\\S+.*";
            dataTable
                .column(15)
                .search(search, true, false).draw();
        } else if (selected == 'No Phone') {
            var search = "^$";
            dataTable
                .column(15)
                .search(search, true, false).draw();
        } else {
            dataTable
                .column(15)
                .search('', true, false).draw();
        }
    });

    $('button#clear-filter-contacts-btn').on('click', function(e) {
        e.preventDefault();
        $('select#religion').val([]).change();
        $('select#faith_tradition').val([]).change();
        $('select#denomination').val([]).change();
        $('select#judicatory_body').val([]).change();
        $('select#contact_address').val([]).change();
        $('select#contact_type').val([]).change();
        $('select#contact_languages').val([]).change();
        $('select#contact_borough').val([]).change();
        $('select#contact_zipcode').val([]).change();
        $('select#phone').val(['All']).change();
        $('select#email').val(['All']).change();
    });

    $('button.delete-td').on('click', function(e) {
        e.preventDefault();
        var value = $(this).val();
        $('input#contact_recordid').val(value);
    });

    $(document).ready(function(){  
        setTimeout(function(){
            var locations = <?php print_r(json_encode($locations)) ?>;        
            var maplocation = <?php print_r(json_encode($map)) ?>;            

            if(maplocation.active == 1){
                avglat = maplocation.lat;
                avglng = maplocation.long;
                zoom = maplocation.zoom;
            }
            else
            {
                avglat = 40.730981;
                avglng = -73.998107;
                zoom = 12;
            }

            latitude = locations[0].location_latitude;
            longitude = locations[0].location_longitude;

            if(latitude == null){
                latitude = avglat;
                longitude = avglng;
            }
            
            // var mymap = new GMaps({
            //     el: '#map',
            //     lat: latitude,
            //     lng: longitude,
            //     zoom: zoom
            // });

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: zoom,
                center: {lat: parseFloat(latitude), lng: parseFloat(longitude)}
            });
            var locations_info = locations.map((value) => {
                return {
                    lat: parseFloat(value.location_latitude),
                    lng: parseFloat(value.location_longitude), 
                }
            })            
           
            var markers = locations_info.map(function(location, i) {
                return new google.maps.Marker({
                    position: location
                });
            });

            var markerCluster = new MarkerClusterer(map, markers,
                {imagePath: "{{asset('images/m')}}"});

            // $.each( locations, function(index, value ){
            //         // console.log(locations);                      

            //         if(value.location_latitude){
            //             mymap.addMarker({

            //                 lat: value.location_latitude,
            //                 lng: value.location_longitude,
            //                 title: value.city,
                                
            //                 infoWindow: {
            //                     maxWidth: 250
            //                 }
            //             });
            //         }
            //     });

        }, 2000)
    });
    

</script>
@endsection

