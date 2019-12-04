@extends('layouts.app')
@section('title')
Organizations
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
button[data-id="type"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="tag"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="borough"] {
    height: 100%;
    border: 1px solid #ddd;
}
.sel-label-org {
    width: 15%;
}
#clear-filter-org-btn {
    width: 100%;
}
#tbl-organization_wrapper {
    overflow-x: scroll;
}



</style>

@section('content')
<div class="wrapper">
    <!-- Page Content Holder -->
    <div id="organizations-content" class="container">
        <form action="/organizations/action_group" id="organizations_form" method="POST">
        {{ csrf_field() }}
            <div class="row">
                <div class="col-md-6 p-20">                        
                    <div class="form-group row">
                        <label class="control-label sel-label-org col-sm-3">Religion: </label>
                        <div class="col-sm-9 col-sm-6 col-xs-12" id="religion-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="religion" name="religion">
                            @foreach($religion_list as $key => $religion)
                                <option value="{{$religion}}">{{$religion}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label sel-label-org col-sm-3">Faith Tradition: </label>
                        <div class="col-sm-9 col-sm-6 col-xs-12" id="faith_tradition-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="faith_tradition" name="faith_tradition">
                            @foreach($faith_tradition_list as $key => $faith_tradition)
                                <option value="{{$faith_tradition}}">{{$faith_tradition}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label sel-label-org col-sm-3">Denomination: </label>
                        <div class="col-sm-9 col-sm-6 col-xs-12" id="denomination-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="denomination" name="denomination">
                            @foreach($denomination_list as $key => $denomination)
                                <option value="{{$denomination}}">{{$denomination}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="control-label sel-label-org col-sm-3">Judicatory Body: </label>
                        <div class="col-sm-9 col-sm-6 col-xs-12" id="judicatory_body-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="judicatory_body" name="judicatory_body">
                            @foreach($judicatory_body_list as $key => $judicatory_body)
                                <option value="{{$judicatory_body}}">{{$judicatory_body}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="control-label sel-label-org col-sm-3">Type: </label>
                        <div class="col-sm-9 col-sm-6 col-xs-12" id="type-div">
                            <select class="form-control selectpicker"  multiple data-live-search="true" id="type" name="type">
                            @foreach($type_list as $key => $type)
                                <option value="{{$type}}">{{$type}}</option>
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
                    <input type="hidden" id="type_list" name="type_list">
                    <input type="hidden" id="tag_list" name="tag_list">
                    
                    <div class="form-group row">
                        <div class="col-sm-12 col-xs-12" id="clear-btn-div">
                            <button class="btn btn-success btn-rounded" id="clear-filter-org-btn"><i class="fa fa-refresh"></i> Clear Filters</button>
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

                <input type="hidden" id="organization_map_image" name="organization_map_image">

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
                    <table class="table table-striped jambo_table bulk_action nowrap" id="tbl-organization">
                        <thead>
                            <tr>
                                <th style="visibility: hidden;">Open Action</th>
                                <th style="visibility: hidden;">Delete Action</th>
                                <th class="default-inactive">Id</th>
                                <th class="default-active">Name</th>
                                <th class="default-active">Religion</th>
                                <th class="default-active">Faith Tradition</th>
                                <th class="default-active">Denomination</th>
                                <th class="default-active">Judicatory Body</th>                            
                                <th class="default-inactive">Organization Type</th>
                                <th class="default-inactive">Website</th>
                                <th class="default-inactive">Facebook</th>
                                <th class="default-inactive">Internet Access</th>
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
                    <form action="/organization_delete_filter" method="POST" id="organization_delete_filter">
                        {!! Form::token() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Delete Organization</h4>
                        </div>
                        <div class="modal-body">
                            
                            <input type="hidden" id="organization_recordid" name="organization_recordid">
                            <h4>Are you sure to delete this organization?</h4>
                            
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
</div>

@endsection
@section('customScript')
<script type="text/javascript" src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/scroller/2.0.1/js/dataTables.scroller.min.js"></script>
<script src="{{asset('js/markerclusterer.js')}}"></script>
<script>
    var dataTable;
    var filter_map = "";
    var marks = [];

    $(document).ready(function() {
        $('#waiting').hide();
        sessionStorage.setItem('check_marks', '');
        dataTable = $('#tbl-organization').DataTable({
            "scrollX": true,
            "dom": 'lBfrtip',
            "order": [[ 2, 'desc' ]],
            "buttons": [{
                extend: 'colvis',
                columns: ':gt(8)'
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
                    var filter_religion = data.columns[4].search.value;
                    var filter_faith_tradition = data.columns[5].search.value;
                    var filter_denomination = data.columns[6].search.value;
                    var filter_judicatory_body = data.columns[7].search.value;
                    var filter_type = data.columns[8].search.value;
                    var filter_tag = data.columns[13].search.value;
                    var check_marks = sessionStorage.getItem('check_marks');
                  
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $('#waiting').show();
                    $.ajax({
                        type: "POST",
                        url: "/get_all_organizations",
                        data: {
                            start: start,
                            length: length,
                            search_term: search_term,
                            filter_religion: filter_religion,
                            filter_faith_tradition: filter_faith_tradition,
                            filter_denomination: filter_denomination,
                            filter_judicatory_body: filter_judicatory_body,
                            filter_type: filter_type,                            
                            filter_tag: filter_tag,
                            filter_map: filter_map
                        },
                        success: function (response) {
                            $('#waiting').hide();
                           
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
                                $('input#organization_recordid').val(value);
                            });

                            var locations = response.filtered_locations_list;    
                            console.log(locations);
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

                            if (sessionStorage.getItem('check_marks') == 'true') {
                                var poly_coordinate_list = sessionStorage.getItem('poly_coordinate_list');
                                var point_list = JSON.parse(poly_coordinate_list);
                                var poly = new google.maps.Polygon({
                                    paths: point_list,
                                    strokeColor: '#000000',
                                    strokeOpacity: 1.0,
                                    strokeWeight: 3
                                });
                                poly.setMap(map);
                            }
                            else {
                                var poly = new google.maps.Polygon({
                                    strokeColor: '#000000',
                                    strokeOpacity: 1.0,
                                    strokeWeight: 3
                                });
                            }

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
                                if (value) {
                                    return {
                                        lat: parseFloat(value.location_latitude),
                                        lng: parseFloat(value.location_longitude), 
                                    }
                                }
                            })
                            
                            var markers = locations_info.map(function(location, i) {
                                return new google.maps.Marker({
                                    position: location
                                });
                            });
                            var markerCluster = new MarkerClusterer(map, markers,
                                {imagePath: "{{asset('images/m')}}"});

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
                                
                                filter_map = JSON.stringify(filtered_points);
                                
                                dataTable.ajax.reload();
                                sessionStorage.setItem('check_marks', 'true');                               
                                console.log('=========after filter===========');
                                console.log(marks); 

                                var poly_coordinate_list = [];
                                for(var i = 0; i < marks.length; i ++) {
                                    var poly_coordinate = {
                                        lat: marks[i].position.lat(), 
                                        lng: marks[i].position.lng()
                                    };
                                    poly_coordinate_list.push(poly_coordinate);
                                }
                                sessionStorage.setItem('poly_coordinate_list', JSON.stringify(poly_coordinate_list)); 
                                console.log(poly_coordinate_list); 
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
                                for(i = 0; i < Math.min(350  , markers.length); i ++) {
                                    var lat = markers[i].position.lat();
                                    var lng = markers[i].position.lng();
                                    var markers_param = "&markers="+ lat + ", " + lng;
                                    img_url += markers_param;
                                }
                                console.log(img_url);
                                $('input#organization_map_image').val(img_url);
                                $('#organizations_form').submit();
                            });
                        },
                        error: function (data) {
                            if (data.status == 0 || data.status == 414) {
                                console.log('Organizations in filtered Ploygon are too much. Enlarge Map and filter in more detailed area.');
                            }
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
                        return '<a class="btn btn-primary open-td" href="/organization/' + row[2] + '" style="color: white;">Open</a>';
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
        $("#religion").selectpicker("");
        $("#faith_tradition").selectpicker("");
        $("#denomination").selectpicker("");
        $("#judicatory_body").selectpicker("");
        $("#type").selectpicker("");
        $("#location").selectpicker("");  
        $("#tag").selectpicker("");
    })
    $('select#religion').on('change', function() {
        
        var selectedList = $(this).val();        
        $('input#religion_list').val(selectedList);
        search = selectedList.join('|')
        dataTable
            .column(4)
            .search(search ? search : '', true, false).draw();
            // .search(search ? '^' + search + '$' : '', true, false).draw();
    });
    $('select#faith_tradition').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#faith_tradition_list').val(selectedList);
        search = selectedList.join('|')

        dataTable
            .column(5)
            .search(search ? search : '', true, false).draw();
    });
    $('select#denomination').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#denomination_list').val(selectedList);
        search = selectedList.join('|')
 
        dataTable
            .column(6)
            .search(search ? search : '', true, false).draw();
    });
    $('select#judicatory_body').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#judicatory_body_list').val(selectedList);
        search = selectedList.join('|')

        dataTable
            .column(7)
            .search(search ? search : '', true, false).draw();
    });
    $('select#type').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#type_list').val(selectedList);
        search = selectedList.join('|')
 
        dataTable
            .column(8)
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

    $('button#clear-filter-org-btn').on('click', function(e) {
        e.preventDefault();
        window.location.reload(true);
    });

    $('button.delete-td').on('click', function() {
        var value = $(this).val();
        $('input#organization_recordid').val(value);
    });

    $(document).ready(function(){  
        setTimeout(function(){
            var locations = <?php print_r(json_encode($locations)) ?>;        
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

    $(document).ready(function() {
        var showChar = 250;
        var ellipsestext = "...";
        var moretext = "More";
        var lesstext = "Less";
        $('.more').each(function() {
        var content = $(this).html();

        if(content.length > showChar) {

            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);

            var html = c + '<span class="moreelipses">'+ellipsestext+'</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';

            $(this).html(html);
        }
    });

    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
        });
    });

</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{$map->api_key}}&libraries=places&callback=initMap"
  async defer></script>
@endsection

