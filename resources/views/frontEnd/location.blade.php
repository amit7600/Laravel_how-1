@extends('layouts.app')
@section('title')
Facility
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


<style type="text/css">
    .table a {
        text-decoration: none !important;
        color: rgba(40, 53, 147, .9);
        white-space: normal;
    }

    .footable.breakpoint>tbody>tr>td>span.footable-toggle {
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

    #map {
        position: relative !important;
        z-index: 0 !important;
    }

    @media (max-width: 768px) {
        .property {
            padding-left: 30px !important;
        }

        #map {
            display: block !important;
            width: 100% !important;
        }
    }

    .morecontent span {
        display: none;

    }

    .morelink {
        color: #428bca;
    }

    button.dt-button {
        display: none !important;
    }

    div#tbl-location-profile-history_filter {
        margin-left: 10px;
    }

    table#tbl-location-profile-history {
        width: 100% !important;
        display: block;
        border-bottom: 0px;
    }

    #tbl-location-profile-history_wrapper {
        overflow-x: scroll;
    }

    #tagging-div {
        margin-top: 12px !important;
    }
</style>

@section('content')
<div class="wrapper">
    <!-- Page Content Holder -->
    <div id="content" class="container">
        <div class="row m-0">
            <div class="col-md-8 pt-15 pb-16 pl-30">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">
                            <a href="">{{$facility->location_name}}
                            </a>
                            <a href="/facility/{{$facility->location_recordid}}/edit"
                                class="btn btn-floating btn-success waves-effect waves-classic" style="float: right;">
                                <i class="icon md-edit" style="margin-right: 0px;"></i>
                            </a>
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Organization:</b></span>
                            <a href="/organization/{{$organization_id}}">{{$organization_name}}</a>
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Facility Type:</b></span>
                            {{ $facility->facility ?  $facility->facility->facility_type : '' }}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Address:</b></span>
                            {{$address_name}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b># Congregations at this
                                    facility:</b></span>
                            {{$facility->location_congregation}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Building Status:</b></span>
                            {{$facility->location_building_status}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Call in Emergency:</b></span>
                            {{$facility->location_call}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Comments:</b></span>
                            {{$facility->location_description}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Latitude:</b></span>
                            {{$facility->location_latitude}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Longitude:</b></span>
                            {{$facility->location_longitude}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>City Council District:</b></span>
                            {{$facility->location_city_council_district}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Community District:</b></span>
                            @php
                            $formated_location_community_district =
                            substr_replace(strval($facility->location_community_district), '-', 1, 0);
                            @endphp
                            {{$formated_location_community_district}}
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 property">
                <div class="pt-10 pl-0 btn-download">
                    <form method="GET" action="/facility/{{$facility->location_recordid}}/tagging"
                        id="location_tagging">
                        <div class="row m-0" id="tagging-div">
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="tokenfield" name="tokenfield"
                                    value="{{$facility->location_tag}}" />
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-secondary btn-tag-save">
                                    <i class="fas fa-save"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card">
                    <div id="map" style="width:initial;margin-top: 0; height: 49vh;"></div>
                </div>
            </div>
            <div class="col-md-8 pt-15 pb-15 pl-30 pl-30">
                <div class="card">
                    <div class="card-block">
                        <h3>Facility Change Log</h3>
                        <table class="table table-striped jambo_table bulk_action nowrap"
                            id="tbl-location-profile-history">
                            <thead>
                                <tr>
                                    <th class="default-inactive">ID</th>
                                    <th class="default-active">Timestamp</th>
                                    <th class="default-active">Field Changed</th>
                                    <th class="default-active">Previous Field Contents</th>
                                    <th class="default-active">New Field Contents</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($facility_history_list as $key => $facility_history)
                                <tr>
                                    <td>{{$facility_history->id}}</td>
                                    <td>{{$facility_history->updated_at}}</td>
                                    <td>{{$facility_history->fieldname_changed}}</td>
                                    <td>{{$facility_history->old_value}}</td>
                                    <td>{{$facility_history->new_value}}</td>
                                </tr>
                                @endforeach
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4 pt-15 pb-15 pl-30 pl-30">
                <div class="card">
                    <div class="card-block">
                        <h3>Comments</h3>
                        <div class="comment-body media-body">
                            @foreach($comment_list as $key => $comment)
                            <a class="comment-author" href="javascript:void(0)">{{$comment->comments_user_firstname}}
                                {{$comment->comments_user_lastname}}</a>
                            <div class="comment-meta">
                                <span class="date">{{$comment->comments_datetime}}</span>
                            </div>
                            <div class="comment-content">
                                <p style="color: black;">{{$comment->comments_content}}</p>
                            </div>
                            <hr>
                            @endforeach
                            <div class="comment-actions">
                                <a class="active" id="reply-btn" href="javascript:void(0)" role="button">Add a
                                    comment</a>
                            </div>
                            <form class="comment-reply" action="/facility/{{$facility->location_recordid}}/add_comment"
                                method="POST">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <textarea class="form-control" id="reply_content" name="reply_content" rows="3">
                                    </textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit"
                                        class="btn btn-primary waves-effect waves-classic">Post</button>
                                    <button type="button" id="close-reply-window-btn"
                                        class="btn btn-link grey-600 waves-effect waves-classic">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="/facility_delete_filter" method="POST" id="facility_delete_filter">
                            {!! Form::token() !!}
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Delete Facility</h4>
                            </div>
                            <div class="modal-body">

                                <input type="hidden" id="facility_recordid" name="facility_recordid">
                                <h4>Are you sure to delete this facility?</h4>

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

<script type="text/javascript" src="http://sliptree.github.io/bootstrap-tokenfield/dist/bootstrap-tokenfield.js">
</script>
<script type="text/javascript"
    src="http://sliptree.github.io/bootstrap-tokenfield/docs-assets/js/typeahead.bundle.min.js"></script>

<script>
    var dataTable;
    $(document).ready(function() {
        dataTable = $('#tbl-location-profile-history').DataTable({
            dom: 'lBfrtip',
            order: [[ 0, 'desc' ]],
            buttons: [{
                extend: 'colvis',
                columns: ':gt(9)'
            }],
            columnDefs: [
                { targets: 'default-inactive', visible: false}
            ]
        });           
    })

    $(document).ready(function() {   
        $('#tokenfield').tokenfield({
        autocomplete: {
            delay: 100
        },
        showAutocompleteOnFocus: true
        });
    });

    $(document).ready(function() {
        $('.comment-reply').hide();
        $('#reply_content').val('');
    });

    $(document).ready(function(){  
        setTimeout(function(){
        var locations = <?php print_r(json_encode($locations)) ?>;       
        var maplocation = <?php print_r(json_encode($map)) ?>;
        console.log(locations);

        if(maplocation.active == 1){
            avglat = maplocation.lat;
            avglng = maplocation.long;
            zoom = maplocation.zoom_profile;
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

        
        var mymap = new GMaps({
            el: '#map',
            lat: latitude,
            lng: longitude,
            zoom: zoom
        });


        $.each( locations, function(index, value ){
                // console.log(locations);
                var name = value.organization==null?'':value.organization.organization_name;
                var serviceid = value.services.length == 0?'':value.services[0].service_recordid;
                var service_name = value.services.length == 0?'':value.services[0].service_name;

                var content = "";
                for(i = 0; i < value.services.length; i ++){
                    content +=  '<a href="/service/'+value.services[i].service_recordid+'" style="color:#428bca;font-weight:500;font-size:14px;">'+value.services[i].service_name+'</a><br>';
                }
                content += '<p>'+name+'</p>';

                if(value.location_latitude){
                    mymap.addMarker({

                        lat: value.location_latitude,
                        lng: value.location_longitude,
                        title: value.city,
                            
                        infoWindow: {
                            maxWidth: 250,
                            content: (content)
                        }
                    });
                }
            });
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

    $('button.delete-td').on('click', function() {
        var value = $(this).val();
        $('input#facility_recordid').val(value);
    });

    $("#reply-btn").on('click', function(e) {
        e.preventDefault();
        $('.comment-reply').show();
    });
    $("#close-reply-window-btn").on('click', function(e) {
        e.preventDefault();
        $('.comment-reply').hide();
    });
    
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{$map->api_key}}&libraries=places&callback=initMap" async
    defer>
</script>
@endsection