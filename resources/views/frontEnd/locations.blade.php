@extends('layouts.app')
@section('title')
Locations
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
.morecontent span {
  display: none;

}
.morelink{
  color: #428bca;
}
button[data-id="type"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="borough"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="tag"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="zipcode"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="address"] {
    height: 100%;
    border: 1px solid #ddd;
}
.sel-label-org {
    width: 15%;
}
#clear-filter-locations-btn {
    width: 100%;
}
#tbl-location_wrapper {
    overflow-x: scroll;
}
</style>

@section('content')
<div class="wrapper">
    <!-- Page Content Holder -->
    <div id="locations-content" class="container">
        <form action="/facilities/action_group" method="GET">
            <div class="row">
                <div class="col-sm-8 p-20">                        
                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4">Type: </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="type-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="type" name="type">
                            @foreach($location_types as $key => $location_type)
                                <option value="{{$location_type->location_type}}">{{$location_type->location_type}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4">Address: </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="address-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="address" name="address">
                            @foreach($address_address_list as $key => $address_address)
                                <option value="{{$address_address}}">{{$address_address}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4">Borough: </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="borough-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="borough" name="borough">
                            @foreach($address_city_list as $key => $address_city)
                                <option value="{{$address_city}}">{{$address_city}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4">Zipcode: </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="zipcode-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="zipcode" name="zipcode">
                            @foreach($address_zipcode_list as $key => $address_zipcode)
                                <option value="{{$address_zipcode}}">{{$address_zipcode}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4">Tag: </label>
                        <div class="col-sm-6 col-sm-6 col-xs-12" id="tag-div">
                            <select class="form-control selectpicker" data-live-search="true" id="tag" name="tag">
                            @foreach($tag_list as $key => $tag)
                                <option value="{{$tag}}">{{$tag}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>  

                    <input type="hidden" id="address_list" name="address_list"> 
                    <input type="hidden" id="borough_list" name="borough_list">
                    <input type="hidden" id="zipcode_list" name="zipcode_list">
                    <input type="hidden" id="type_list" name="type_list">
                    <input type="hidden" id="tag_list" name="tag_list">
                    
                    <div class="form-group row">
                        <label class="control-label sel-label-org pl-4"></label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="clear-btn-div">
                            <button class="btn btn-success btn-rounded" id="clear-filter-locations-btn"><i class="fa fa-refresh"></i> Clear Filters</button>
                        </div>
                    </div>        
                </div>
                <div class="col-md-4 property">
                    <div class="card">
                        <div id="map" style="width:initial;margin-top: 0;height: 50vh;"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row mt-5">
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-primary btn-rounded" name="btn_submit" value="download_csv" id="download_csv" style="width: 100%;">Export CSV</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-secondary btn-rounded" name="btn_submit" value="download_pdf" id="download_pdf" style="width: 100%;">Export PDF</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 p-20"> 
                    <table class="table table-striped jambo_table bulk_action nowrap" id="tbl-location">
                        <thead>
                            <tr>
                                <th class="default-active"></th>
                                <th class="default-active"></th>
                                <th class="default-inactive">Id</th>
                                <th class="default-active">Organization</th>
                                <th class="default-active">Address</th>
                                <th class="default-active">Congregation</th>
                                <th class="default-active">Building Status</th>
                                <th class="default-active">Call in Emergency</th>
                                <th class="default-inactive">Facility Name</th>
                                <th class="default-inactive">Facility Type</th>
                                <th class="default-inactive">Zipcode</th>
                                <th class="default-inactive">Borough</th>
                                <th class="default-inactive">Comments</th>
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
                    <form action="/facility_delete_filter" method="POST" id="facility_delete_filter">
                        {!! Form::token() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Delete Facility</h4>
                        </div>
                        <div class="modal-body">
                            
                            <input type="hidden" id="facility_recordid" name="facility_recordid">
                            <h4>Are you sure delete this facility?</h4>
                            
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
   

    $(document).ready(function() {
        dataTable = $('#tbl-location').DataTable({
            "scrollX": true,
            "dom": 'lBfrtip',
            "order": [[ 2, 'desc' ]],
            "buttons": [{
                extend: 'colvis',
                columns: [8, 9, 10, 12, 13]
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
                    var filter_address = data.columns[4].search.value;
                    var filter_borough = data.columns[11].search.value;
                    var filter_zipcode = data.columns[10].search.value;
                    var filter_type = data.columns[9].search.value;
                    var filter_tag = data.columns[13].search.value;
                  
                    console.log(data);
                    console.log(data.columns);
              
                    $.ajax({
                        type: "GET",
                        url: "/get_all_facilities?start=" + start + "&length=" + length
                            + "&search_term=" + search_term
                            + "&filter_address=" + filter_address
                            + "&filter_borough=" + filter_borough
                            + "&filter_zipcode=" + filter_zipcode
                            + "&filter_type=" + filter_type
                            + "&filter_tag=" + filter_tag,
                        success: function (response) {
                            callback({
                                draw: data.draw,
                                data: response.data,
                                recordsTotal: response.recordsTotal,
                                recordsFiltered: response.recordsFiltered
                            });
                            $('button.delete-td').on('click', function(e) {
                                e.preventDefault();
                                var value = $(this).val();
                                $('input#facility_recordid').val(value);
                            });

                            var locations = response.filtered_locations_list;       
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
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                },
            "columnDefs": [
                { targets: 'default-inactive', visible: false},
                {
                    "targets": 0,
                    "data": null,
                    "render": function ( data, type, row ) {
                        return '<a class="btn btn-primary open-td" href="/facility/' + row[2] + '" style="color: white;">Open</a>';
                    }
                   
                },
                {
                    "targets": 1,
                    "data": null,
                    "render": function ( data, type, row ) {
                        return '<button class="btn btn-danger delete-td" value="' + row[2] + '" data-toggle="modal" data-target=".bs-delete-modal-lg"><i class="fa fa-fw fa-remove"></i>Delete</button>';
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
        $("#address").selectpicker("");
        $("#borough").selectpicker("");
        $("#zipcode").selectpicker("");
        $("#type").selectpicker("");
        $("#tag").selectpicker("");
    })

    $('select#address').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#address_list').val(selectedList);
        search = selectedList.join('|')
        dataTable
            .column(4)
            .search(search ? search : '', true, false).draw();
    });
    $('select#borough').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#borough_list').val(selectedList);
        search = selectedList.join('|')
        dataTable
            .column(11)
            .search(search ? search : '', true, false).draw();
    });
    $('select#zipcode').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#zipcode_list').val(selectedList);
        search = selectedList.join('|')
        dataTable
            .column(10)
            .search(search ? search : '', true, false).draw();
    });
    $('select#type').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#type_list').val(selectedList);
        search = selectedList.join('|')
        dataTable
            .column(9)
            .search(search ? search : '', true, false).draw();
    });
    $('select#tag').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#tag_list').val(selectedList);
        search = selectedList
 
        dataTable
            .column(13)
            .search(search ? search : '', true, false).draw();
    });
    $('button#clear-filter-locations-btn').on('click', function(e) {
        e.preventDefault();
        window.location.reload(true);
    });

    $(document).ready(function(){  
        setTimeout(function(){
            var locations = <?php print_r(json_encode($locations)) ?>;        
            var maplocation = <?php print_r(json_encode($map)) ?>;
            console.log(locations);

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

        }, 2000)
    });

</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{$map->api_key}}&libraries=places&callback=initMap"
  async defer>
</script>
@endsection

