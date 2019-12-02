@extends('layouts.app')
@section('title')
Contacts
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.0.1/css/scroller.dataTables.min.css">

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
button[data-id="tag"] {
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
        <form action="/contacts/action_group" id="contacts_form" method="POST">
        {{ csrf_field() }}
            <div class="row">
                <div class="col-md-6 p-10">                        
                    <div class="form-group row">
                        <label class="control-label sel-label-org col-sm-3">Religion: </label>
                        <div class="col-sm-9 col-xs-12" id="religion-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="religion" name="religion">
                            @foreach($organization_religions as $key => $organization_religion)
                                <option value="{{$organization_religion->organization_religion}}">{{$organization_religion->organization_religion}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label sel-label-org col-sm-3">Faith Tradition: </label>
                        <div class="col-sm-9 col-xs-12" id="faith_tradition-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="faith_tradition" name="faith_tradition">
                            @foreach($faith_tradition_list as $key => $faith_tradition)
                                <option value="{{$faith_tradition}}">{{$faith_tradition}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label sel-label-org col-sm-3">Denomination: </label>
                        <div class="col-sm-9 col-xs-12" id="denomination-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="denomination" name="denomination">
                            @foreach($denomination_list as $key => $denomination)
                                <option value="{{$denomination}}">{{$denomination}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="control-label sel-label-org col-sm-3">Judicatory Body: </label>
                        <div class="col-sm-9 col-xs-12" id="judicatory_body-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="judicatory_body" name="judicatory_body">
                            @foreach($organization_judicatory_bodys as $key => $organization_judicatory_body)
                                <option value="{{$organization_judicatory_body->organization_judicatory_body}}">{{$organization_judicatory_body->organization_judicatory_body}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="control-label sel-label-org col-sm-3">Email: </label>
                        <div class="col-sm-9 col-xs-12" id="email_body-div">
                            <select class="form-control" id="email" name="email">
                                <option value="All" checked>All</option>
                                <option value="Has Email">Has Email</option>
                                <option value="No Email">No Email</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label sel-label-org col-sm-3">Phone: </label>
                        <div class="col-sm-9 col-xs-12" id="phone_body-div">
                            <select class="form-control" id="phone" name="phone">
                                <option value="All" checked>All</option>
                                <option value="Has Email">Has Phone</option>
                                <option value="No Email">No Phone</option>
                            </select>
                        </div>
                    </div>   
                    <div class="form-group row">
                        <label class="control-label sel-label-org col-sm-3">Address: </label>
                        <div class="col-sm-9 col-xs-12" id="address-div">
                        <select class="form-control selectpicker"  multiple data-live-search="true" id="contact_address" name="contact_address">
                            @foreach($address_address_list as $key => $address_address)
                                <option value="{{$address_address}}">{{$address_address}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label sel-label-org col-sm-3">Contact Type: </label>
                        <div class="col-sm-9 col-xs-12" id="contact_type-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="contact_type" name="contact_type">
                            @foreach($contact_types as $key => $contact_type)
                                <option value="{{$contact_type->contact_type}}">{{$contact_type->contact_type}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="control-label sel-label-org col-sm-3">Languages: </label>
                        <div class="col-sm-9 col-xs-12" id="contact_languages-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="contact_languages" name="contact_languages">
                            @foreach($contact_languages as $key => $contact_language)
                                <option value="{{$contact_language->contact_languages_spoken}}">{{$contact_language->contact_languages_spoken}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label sel-label-org col-sm-3">Borough: </label>
                        <div class="col-sm-9 col-xs-12" id="contact_borough-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="contact_borough" name="contact_borough">
                            @foreach($address_city_list as $key => $address_city)
                                <option value="{{$address_city}}">{{$address_city}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="control-label sel-label-org col-sm-3">Zipcode: </label>
                        <div class="col-sm-9 col-xs-12" id="contact_zipcode-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="contact_zipcode" name="contact_zipcode">
                            @foreach($address_zipcode_list as $key => $address_zipcode)
                                <option value="{{$address_zipcode}}">{{$address_zipcode}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>   
                    <div class="form-group row">
                        <label class="control-label sel-label-org col-sm-3">Tag: </label>
                        <div class="col-sm-9 col-sm-6 col-xs-12" id="tag-div">
                            <select class="form-control selectpicker" data-live-search="true" id="tag" name="tag">
                            @foreach($tag_list as $key => $tag)
                                <option value="{{$tag}}">{{$tag}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>

                    <input type="hidden" id="religion_list" name="religion_list"> 
                    <input type="hidden" id="faith_tradition_list" name="faith_tradition_list">
                    <input type="hidden" id="denomination_list" name="denomination_list">
                    <input type="hidden" id="judicatory_body_list" name="judicatory_body_list">
                    <input type="hidden" id="contact_address_list" name="contact_address_list">
                    <input type="hidden" id="contact_type_list" name="contact_type_list">
                    <input type="hidden" id="contact_languages_list" name="contact_languages_list">
                    <input type="hidden" id="contact_borough_list" name="contact_borough_list">
                    <input type="hidden" id="contact_zipcode_list" name="contact_zipcode_list">
                    <input type="hidden" id="contact_map_image" name="contact_map_image">
                    <input type="hidden" id="tag_list" name="tag_list">

                    <div class="form-group row">
                        <div class="col-sm-12 col-xs-12" id="clear-btn-div">
                            <button class="btn btn-success btn-rounded" id="clear-filter-contacts-btn"><i class="fa fa-refresh"></i> Clear Filters</button>
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group row mt-5">
                                <div class="col-sm-6">
                                    <div class="dropdown" style="width: 100%;">
                                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton-group" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100%;">
                                            Group
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-group" style="width: 100%;">
                                            <button type="submit" class="btn btn-info btn-rounded" name="btn_submit" value="save-to-filter-dynamic-group" id="save-to-filter-dynamic-group" style="width: 100%;">Save Filter as Dynamic Group</button>
                                            <button type="submit" class="btn btn-info btn-rounded" name="btn_submit" value="add-to-new-static-group-btn" id="add-to-new-static-group-btn" style="width: 100%;">Add to New Static Group</button>
                                            <button type="submit" class="btn btn-info btn-rounded" name="btn_submit" value="add-to-existing-static-group-btn" id="add-to-existing-static-group-btn" style="width: 100%;">Add to Existing Static Group</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="dropdown" style="width: 100%;">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton-download" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100%;">
                                            Download
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-download" style="width: 100%;">
                                            <button type="submit" class="btn btn-info btn-rounded" name="btn_submit" value="download_csv" id="download_csv" style="width: 100%;">Export CSV</button>
                                            <button type="submit" class="btn btn-info btn-rounded" name="btn_submit" value="download_pdf" id="download_pdf" style="width: 100%;">Export PDF</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="waiting" style="text-align: center; margin-top: 50px;">
                        <i class="fa fa-spinner fa-spin fa-3x fa-fw margin-bottom" style="font-size: 100px;"></i>
                        <span class="sr-only">Loading...</span>
                    </div>
                    
                </div>
                <div class="col-md-6 property">
                    <div class="card">
                        <div class="form-group row mt-5">
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-secondary" id="enable-polygon-btn" style="width: 100%;">Draw</button>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-primary form-control" id="filter-polygon-btn">Apply</button>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-success form-control" id="reset-filter-polygon-btn" onClick="document.location.reload(true)">Reset</button>
                            </div>
                        </div>
                        <div id="map" style="width:initial;margin-top: 10px;height: 50vh;"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <input type="hidden" id="checked_terms" name="checked_terms">
                </div> 
                
                <div class="col-sm-12 p-10"> 
                    <table class="table table-striped jambo_table bulk_action nowrap" id="tbl-contact">
                        <thead>
                            <tr>
                                <th></th>   
                                <th style="visibility: hidden;">Open Action</th>
                                <th style="visibility: hidden;">Delete Action</th>                         
                                <th class="default-inactive">Id</th>
                                <th class="default-active">First Name</th>
                                <th class="default-active">Middle Name</th>
                                <th class="default-active">Last Name</th>
                                <th class="default-active">Organization</th>
                                <th class="default-active">Contact Type</th>
                                <th class="default-active">Religious Prefix</th>

                                <th class="default-active">Job Title</th> 
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

                                <th class="default-inactive">Address1</th> 
                                <th class="default-inactive">Borough</th> 
                                <th class="default-inactive">Zipcode</th> 
                                <th class="default-inactive">Organization ID</th> 
                                <th class="default-inactive">Organization Name</th> 
                                <th class="default-inactive">Tag</th>
                                                       
                            </tr>
                        </thead>
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
<script type="text/javascript" src="https://cdn.datatables.net/scroller/2.0.1/js/dataTables.scroller.min.js"></script>
<script src="{{asset('js/markerclusterer.js')}}"></script>
<script>
    var dataTable;
    var checked_terms_set;
    var filter_map = "";
    var marks = [];
    $(document).ready(function() {
        $('#waiting').hide();
        sessionStorage.setItem('check_marks', '');
        dataTable = $('#tbl-contact').DataTable({
            "scrollX": true,
            "dom": 'lBfrtip',
            "order": [[ 2, 'desc' ]],
            "buttons": [{
                extend: 'colvis',
                columns: [11, 12, 13, 14, 19, 30]
            }],
            "serverSide": true,          
            "searching": true,                   
            "scrollY": 500,
            "scroller": {
                "loadingIndicator": true
            },
            
            "ajax": function (data, callback, settings) {
                    var start = data.start;
                    var length = data.length;
                    var search_term = data.search.value;
                    var filter_contact_borough = data.columns[26].search.value;
                    var filter_contact_zipcode = data.columns[27].search.value;
                    var filter_tag = data.columns[30].search.value;
                    var filter_contact_languages = data.columns[11].search.value;
                    var filter_contact_address = data.columns[25].search.value;
                    var filter_contact_type = data.columns[8].search.value;
                    var filter_religion = data.columns[21].search.value;
                    var filter_faith_tradition = data.columns[22].search.value;
                    var filter_denomination = data.columns[23].search.value;
                    var filter_judicatory_body = data.columns[24].search.value;
                    var filter_email = data.columns[14].search.value;
                    var filter_phone = data.columns[16].search.value;
                    var check_marks = sessionStorage.getItem('check_marks');
                  
                    console.log(data);
                    console.log(data.columns);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $('#waiting').show();
                    $.ajax({
                        type: "POST",
                        url: "/get_all_contacts",
                        data: {
                            start: start,
                            length: length,
                            search_term: search_term,
                            filter_contact_borough: filter_contact_borough,
                            filter_contact_zipcode: filter_contact_zipcode,
                            filter_contact_languages: filter_contact_languages,
                            filter_contact_address: filter_contact_address,
                            filter_contact_type: filter_contact_type,
                            filter_religion: filter_religion,
                            filter_faith_tradition: filter_faith_tradition,
                            filter_denomination: filter_denomination,
                            filter_judicatory_body: filter_judicatory_body,
                            filter_email: filter_email,
                            filter_phone: filter_phone,
                            filter_tag: filter_tag,
                            filter_map: filter_map,
                        },
                        success: function (response) {
                            $('#waiting').hide();
                            console.log(response);
                            callback({
                                draw: data.draw,
                                data: response.data,
                                recordsTotal: response.recordsTotal,
                                recordsFiltered: response.recordsFiltered,
                                marks: response.marks
                            });
                            $('button.delete-td').on('click', function(e) {
                                e.preventDefault();
                                var value = $(this).val();
                                $('input#contact_recordid').val(value);
                            });

                            console.log(check_marks);
                            if (sessionStorage.getItem('check_marks') == 'true') {
                                return;
                            }

                            var locations = response.filtered_locations_list;
                            var maplocation = <?php print_r(json_encode($map)) ?>;            
                            if(maplocation.active == 1){
                                avglat = maplocation.lat;
                                avglng = maplocation.long;
                                zoom = maplocation.zoom * 15;
                            }
                            else
                            {
                                avglat = 40.730981;
                                avglng = -73.998107;
                                zoom = 12 * 15;
                            }
                            latitude = locations[0].location_latitude;
                            longitude = locations[0].location_longitude;
                            if(latitude == null){
                                latitude = avglat;
                                longitude = avglng;
                            }
                            var map = new google.maps.Map(document.getElementById('map'), {
                                zoom: zoom,
                                center: {lat: parseFloat(latitude), lng: parseFloat(longitude)}
                            });

                            var poly = new google.maps.Polygon({
                                strokeColor: '#000000',
                                strokeOpacity: 1.0,
                                strokeWeight: 3
                            });
                            // poly.setMap(map);
                            
                            var marks = [];

                            $('#enable-polygon-btn').on('click', function(e) {
                                e.preventDefault();
                                poly = new google.maps.Polygon({
                                    strokeColor: '#000000',
                                    strokeOpacity: 1.0,
                                    strokeWeight: 3
                                });
                                poly.setMap(map);
                                map.addListener('click', addLatLng);
                            });

                            $('#filter-polygon-btn').on('click', function(e) {
                                e.preventDefault();
                                google.maps.event.clearListeners(map, 'click');
                            });

                            $('#reset-filter-polygon-btn').on('click', function(e) {
                                e.preventDefault();
                                google.maps.event.clearListeners(map, 'click');
                                poly.setMap(null);
                                clearMarkers();
                                marks = [];
                            });

                            // Sets the map on all markers in the array.
                            function setMapOnAll(map) {
                                for (var i = 0; i < marks.length; i++) {
                                    marks[i].setMap(map);
                                }
                            }

                            // Removes the markers from the map, but keeps them in the array.
                            function clearMarkers() {
                                setMapOnAll(null);
                            }

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
                +                {imagePath: "{{asset('images/m')}}"});

                            function addLatLng(event) {
                                var path = poly.getPath();
                                // Because path is an MVCArray, we can simply append a new coordinate
                                // and it will automatically appear.
                                path.push(event.latLng);

                                // Add a new marker at the new plotted point on the polyline.
                                var marker = new google.maps.Marker({
                                    position: event.latLng,
                                    title: '#' + path.getLength(),
                                    map: map
                                });
                                marks.push(marker);
                            }

                            google.maps.Polygon.prototype.Contains = function (point) {
                                
                                var crossings = 0,
                                    path = this.getPath();
                                // for each edge
                                for (var i = 0; i < path.getLength(); i++) {
                                    var a = path.getAt(i),
                                        j = i + 1;
                                    if (j >= path.getLength()) {
                                        j = 0;
                                    }
                                    var b = path.getAt(j);
                                    if (rayCrossesSegment(point, a, b)) {
                                        crossings++;
                                    }
                                }
                                // odd number of crossings?
                                return (crossings % 2 == 1);
                                function rayCrossesSegment(point, a, b) {
                                    var px = point.lng(),
                                        py = point.lat(),
                                        ax = a.lng(),
                                        ay = a.lat(),
                                        bx = b.lng(),
                                        by = b.lat();
                                    if (ay > by) {
                                        ax = b.lng();
                                        ay = b.lat();
                                        bx = a.lng();
                                        by = a.lat();
                                    }
                                    // alter longitude to cater for 180 degree crossings
                                    if (px < 0) {
                                        px += 360;
                                    }
                                    if (ax < 0) {
                                        ax += 360;
                                    }
                                    if (bx < 0) {
                                        bx += 360;
                                    }
                                    if (py == ay || py == by) py += 0.00000001;
                                    if ((py > by || py < ay) || (px > Math.max(ax, bx))) return false;
                                    if (px < Math.min(ax, bx)) return true;
                                    var red = (ax != bx) ? ((by - ay) / (bx - ax)) : Infinity;
                                    var blue = (ax != px) ? ((py - ay) / (px - ax)) : Infinity;
                                    return (blue >= red);
                                }
                            };

                            $('#filter-polygon-btn').on('click', function(e) {
                                e.preventDefault();
                                var filtered_points = [];
                                // var point = new google.maps.LatLng(41.781227, -88.141844);
                                console.log(markers[0].position.lng());
                                console.log(markers[0].position.lat());
                                for (i = 0; i < markers.length; i++) {
                                    var point = new google.maps.LatLng(markers[i].position.lat(), markers[i].position.lng());
                                    if (poly.Contains(point)) {
                                        var lat = markers[i].position.lat();
                                        var lng = markers[i].position.lng();
                                        filtered_points.push({
                                            lat: lat,
                                            lng: lng
                                        });
                                    } 
                                }

                                console.log(filtered_points);
                                
                                filter_map = JSON.stringify(filtered_points);
                                dataTable.ajax.reload();
                                sessionStorage.setItem('check_marks', 'true');
                                console.log('=========after filter===========');
                                console.log(marks);                                  
                            });
                            $('#download_pdf').on('click', function(e) {
                                e.preventDefault();                               
                                var center_lat = map.center.lat();
                                var center_lng = map.center.lng();
                                // var zoom = map.zoom;
                                var zoom = 9;
                                var maptype = map.mapTypeId;
                                var img_url = 'https://maps.googleapis.com/maps/api/staticmap?center='+ center_lat + ', ' + center_lng +
                                         '&zoom='+zoom+'&size=600x300&maptype=' + maptype + '&key=AIzaSyDHW59pLhUQA4IODjApYTVnBdav32ORYYA'
                                for(i = 0; i < Math.min(350, markers.length); i ++) {
                                    var lat = markers[i].position.lat();
                                    var lng = markers[i].position.lng();
                                    var markers_param = "&markers="+ lat + ", " + lng;
                                    img_url += markers_param;
                                }
                                console.log(img_url);
                                $('input#contact_map_image').val(img_url);
                                $('#contacts_form').submit();
                            });
                        },
                        error: function (data) {
                            if (data.status == 0 || data.status == 414) {
                                console.log('Contacts in filtered Ploygon are too much. Enlarge Map and filter in more detailed area.');
                            }
                            console.log('Error:', data);
                        }
                    });
                },
            "columnDefs": [
                { targets: 'default-inactive', visible: false},
                {
                    'targets': 0,
                    'checkboxes': {
                        'selectRow': true
                    },
                },
                {
                    "targets": 1,
                    "data": null,
                    "render": function ( data, type, row ) {
                        return '<a class="btn btn-primary open-td" href="/contact/' + row[0] + '" style="color: white;">Open</a>';
                    }
                   
                },
                {
                    "targets": 2,
                    "data": null,
                    "render": function ( data, type, row ) {
                        return '<button class="btn btn-danger delete-td" value="' + row[0] + '" data-toggle="modal" data-target=".bs-delete-modal-lg"><i class="fa fa-fw fa-remove"></i>Delete</button>';
                    }
                },
                {
                    "targets": 7,
                    "data": null,
                    "render": function ( data, type, row ) {
                        return '<a id="contact_organization_link" style="color: #3949ab; text-decoration: underline;" href="/organization/' + row[28] + '">' + row[29] + '</a>';
                    }
                } 

            ],
            'select': {
                'style': 'multi'
            },
            'scroller': {
                'loadingIndicator': true
            }
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
    });

    $('#add-to-new-static-group-btn').click(function(e){
        if (!checked_terms_set) {
            e.preventDefault();
            var checked_terms = dataTable.column(0).checkboxes.selected();
            $('#checked_terms').val(checked_terms.join(","));
            checked_terms_set = true;
            $(this).trigger('click');
        }
    });

    

    $('select#contact_borough').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#contact_borough_list').val(selectedList);
        search = selectedList.join('|')
        dataTable
            .column(26)
            .search(search ? search : '', true, false).draw();
    });
    $('select#contact_zipcode').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#contact_zipcode_list').val(selectedList);
        search = selectedList.join('|')
        dataTable
            .column(27)
            .search(search ? search : '', true, false).draw();
    });
    $('select#contact_languages').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#contact_languages_list').val(selectedList);
        search = selectedList.join('|')
        dataTable
            .column(11)
            .search(search ? '^' + search + '$' : '', true, false).draw();
    });
    $('select#contact_address').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#contact_address_list').val(selectedList);
        search = selectedList.join('|')
        dataTable
            .column(25)
            .search(search ? search : '', true, false).draw();
    });
    $('select#contact_type').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#contact_type_list').val(selectedList);
        search = selectedList.join('|')
        dataTable
            .column(8)
            .search(search ? search : '', true, false).draw();
    });
    $('select#religion').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#religion_list').val(selectedList);
        search = selectedList.join('|')
        dataTable
            .column(21)
            .search(search ? search : '', true, false).draw();
    });
    $('select#faith_tradition').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#faith_tradition_list').val(selectedList);
        search = selectedList.join('|')      
        dataTable
            .column(22)
            .search(search ? search : '', true, false).draw();
    });
    $('select#denomination').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#denomination_list').val(selectedList);
        search = selectedList.join('|')     
        dataTable
            .column(23)
            .search(search ? search : '', true, false).draw();
    });
    $('select#judicatory_body').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#judicatory_body_list').val(selectedList);
        search = selectedList.join('|')       
        dataTable
            .column(24)
            .search(search ? '^' + search + '$' : '', true, false).draw();
    });
    $('select#tag').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#tag_list').val(selectedList);
        search = selectedList
 
        dataTable
            .column(30)
            .search(search ? search : '', true, false).draw();
    });
    $('select#email').on('change', function() {       
        
        var selected = $(this).val();
        if (selected == 'Has Email') {
            var search = "Yes";
            dataTable
                .column(14)
                .search(search, true, false).draw();
        } else if (selected == 'No Email') {
            var search = "No";
            dataTable
                .column(14)
                .search(search, true, false).draw();
        } else {
            dataTable
                .column(14)
                .search('', true, false).draw();
        }
    });

    $('select#phone').on('change', function() {
        
        var selected = $(this).val();
        if (selected == 'Has Phone') {
            var search = "Yes";
            dataTable
                .column(16)
                .search(search, true, false).draw();
        } else if (selected == 'No Phone') {
            var search = "No";
            dataTable
                .column(16)
                .search(search, true, false).draw();
        } else {
            dataTable
                .column(16)
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

    var map;
    var poly;
    

    function initMap(){
        var locations = <?php print_r(json_encode($locations)) ?>;        
        var maplocation = <?php print_r(json_encode($map)) ?>;  
        console.log(maplocation);

        if(maplocation.active == 1){
            avglat = maplocation.lat;
            avglng = maplocation.long;
            zoom = maplocation.zoom * 15;
        }
        else
        {
            avglat = 40.730981;
            avglng = -73.998107;
            zoom = 12 * 15;
        }

        latitude = locations[0].location_latitude;
        longitude = locations[0].location_longitude;

        if(latitude == null){
            latitude = avglat;
            longitude = avglng;
        }
       
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: zoom,
            center: {lat: parseFloat(latitude), lng: parseFloat(longitude)}
        });
        
        var locations_info = locations.map((value) => {
            return {
                lat: parseFloat(value.location_latitude),
                lng: parseFloat(value.location_longitude), 
            }
        });
        
        var markers = locations_info.map(function(location, i) {
            return new google.maps.Marker({
                position: location
            });
        });

        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: "{{asset('images/m')}}"});
    }
    
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{$map->api_key}}&libraries=places&callback=initMap"
  async defer></script>
@endsection


