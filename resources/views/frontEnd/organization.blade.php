@extends('layouts.app')
@section('title')
Organization
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

    #tbl-org-profile-contact_wrapper {
        overflow-x: scroll;
    }

    #tbl-org-profile-contact {
        width: 100% !important;
    }

    .comment-author {
        color: #3949ab !important;
        font-size: 18px !important;
    }

    span.date {
        font-style: italic;
        color: maroon;
    }

    #tagging-div {
        margin-top: 12px !important;
    }
</style>

@section('content')
<div class="wrapper">

    <div id="content" class="container">
        <div class="row m-0">
            <div class="col-md-8 pt-15 pb-15 pl-30">
                <div class="card">
                    <div class="card-block" style="height: 570px;">
                        <h4 class="card-title">
                            <a href="">@if($organization->organization_logo_x)<img
                                    src="{{$organization->organization_logo_x}}" height="80">@endif
                                {{$organization->organization_name}}
                                @if($organization->organization_alternate_name!='')({{$organization->organization_alternate_name}})@endif
                            </a>
                            <a href="/organization/{{$organization->organization_recordid}}/edit"
                                class="btn btn-floating btn-success waves-effect waves-classic" style="float: right;">
                                <i class="icon md-edit" style="margin-right: 0px;"></i>
                            </a>
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Religion:</b></span>
                            {{$organization->religion ? $organization->religion->name : ''}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Organization Type:</b></span>
                            {{$organization->organigationType ? $organization->organigationType->organization_type : ''}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Faith Tradition:</b></span>
                            {{$organization->faith_tradition ? $organization->faith_tradition->name : ''}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Denomination:</b></span>
                            {{$organization->denomination ? $organization->denomination->name : ''}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Judicatory Body:</b></span>
                            {{$organization->judicatory_body ? $organization->judicatory_body->name : ''}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Main Address</b></span>
                            @if(isset($organization->location))
                            @foreach($organization->location as $location)
                            @if(isset($location->address))
                            @foreach($location->address as $address)
                            {{ $address->address_1 }}, {{ $address->address_city }}, {{ $address->address_state }},
                            {{ $address->address_zip_code }} <br>
                            @endforeach
                            @endif
                            @endforeach
                            @endif
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Facebook:</b></span>
                            {{$organization->organization_facebook}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Website:</b></span>
                            {{$organization->organization_url}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Internet Access:</b></span>
                            {{$organization->organization_internet_access}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Comments:</b></span>
                            {{$organization->organization_description}}
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 property">
                <div class="pt-10 pb-10 pl-0 btn-download">
                    <form method="GET" action="/organization/{{$organization->organization_recordid}}/tagging"
                        id="organization_tagging">
                        <div class="row m-0" id="tagging-div">
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="tokenfield" name="tokenfield"
                                    value="{{$organization->organization_tag}}" />
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
                    <div id="map" style="width:initial;margin-top: 0;height: 325px;"></div>
                    <div class="card-block">
                        <div class="p-10">
                            @if(isset($organization->location))
                            @foreach($organization->location as $location)
                            <h4>
                                <span><i class="icon fas fa-building font-size-24 vertical-align-top  "></i>
                                    <a
                                        href="/facility/{{$location->location_recordid}}">{{$location->location_name}}</a>
                                </span>
                            </h4>
                            <h4>
                                <span><i class="icon md-pin font-size-24 vertical-align-top  "></i>
                                    @if(isset($location->address))
                                    @foreach($location->address as $address)
                                    {{ $address->address_1 }}, {{ $address->address_city }},
                                    {{ $address->address_state }}, {{ $address->address_zip_code }}
                                    @endforeach
                                    @endif
                                </span>
                            </h4>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 pt-15 pb-15 pl-30 pl-30">
                <h3>Contacts</h3>
                <div class="card">
                    <div class="card-block">
                        <table class="table table-striped jambo_table bulk_action nowrap" id="tbl-org-profile-contact">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Contact Type</th>
                                    <th>Languages Spoken</th>
                                    <th>Religious Title</th>
                                    <th>Position Title</th>
                                    <th>Other Languages</th>
                                    <th>Pronouns</th>
                                    <th>Organization</th>
                                    <th>Mailing Address</th>
                                    <th>Cell Phone</th>
                                    <th>Office Phone</th>
                                    <th>Emergency Phone</th>
                                    <th>Office Fax</th>
                                    <th>Personal Email</th>
                                    <th>Work Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contacts as $key => $contact)
                                <tr>
                                    <td>
                                        <a class="open-td "
                                            href="/contacts/{{$contact->contact_recordid}}" style="color:#007bff;"
                                            ><i class="fa fa-eye"></i></a>
                                    </td>
                                    <td>{{$contact->contact_recordid}}</td>
                                    <td>{{$contact->contact_first_name}} {{$contact->contact_middle_name}}
                                        {{$contact->contact_last_name}}</td>
                                    <td>{{ $contact->type ? $contact->type->contact_type : ''}}</td>
                                    <td>
                                        @php
                                        $languagesData = [];
                                        if ($contact->contact_languages_spoken != '') {
                                        $languagesData = explode(',',$contact->contact_languages_spoken);
                                        }
                                        @endphp
                                        @foreach ($languagesData as $id)
                                        @foreach ($languages as $langugeDetail)
                                        @if ($langugeDetail->id == (int)$id)
                                        <span class="badge badge-info">{{$langugeDetail->language_name}}</span>
                                        @endif
                                        @endforeach
                                        @endforeach
                                    
                                    </td>
                                    <td>{{$contact->contact_religious_title}}</td>
                                    <td>{{$contact->contact_title}}</td>
                                    <td>{{$contact->contact_other_languages}}</td>
                                    <td>{{$contact->contact_pronouns}}</td>
                                    <td>{{$contact->organization ? $contact->organization['organization_name'] : ''}}</td>
                                    <td>{{$contact->address ? $contact->address['address'] : ''}}</td>
                                    <td>{{$contact->cellphone ? $contact->cellphone['phone_number'] : ''}}</td>
                                    <td>{{$contact->officephone ? $contact->officephone['phone_number'] : ''}}</td>
                                    <td>{{$contact->emergencyphone ? $contact->emergencyphone['phone_number'] : ''}}</td>
                                    <td>{{$contact->faxphone ? $contact->faxphone['phone_number'] : ''}}</td>
                                    <td>{{$contact->contact_personal_email}}</td>
                                    <td>{{$contact->contact_email}}</td>
                                </tr>
                                @endforeach
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4 pt-15 pb-15 pl-30 pl-30">
                <h3>Comments</h3>
                <div class="card">
                    <div class="card-block">
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
                            <form class="comment-reply"
                                action="/organization/{{$organization->organization_recordid}}/add_comment"
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
                        <form action="/organization_delete_filter" method="POST" id="organization_delete_filter">
                            {!! Form::token() !!}
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">×</span>
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
<script type="text/javascript" src="http://sliptree.github.io/bootstrap-tokenfield/dist/bootstrap-tokenfield.js">
</script>
<script type="text/javascript"
    src="http://sliptree.github.io/bootstrap-tokenfield/docs-assets/js/typeahead.bundle.min.js"></script>

<script>
    var dataTable;
    $(document).ready(function() {
        dataTable = $('#tbl-org-profile-contact').DataTable({
            "scrollX": true,           
            dom: 'lBfrtip',
            buttons: [{
                extend: 'colvis',
                columns: ':gt(7)'
            }],
            columnDefs: [
                { targets: 'default-inactive', visible: false}
            ]
        });           
    })
   
    $(document).ready(function() {
        $('.comment-reply').hide();
        $('#reply_content').val('');
    });

    $(document).ready(function() {   
        $('#tokenfield').tokenfield({
        // autocomplete: {
        //     delay: 100
        // },
        showAutocompleteOnFocus: true
        });
    });

    $(document).ready(function(){  
        setTimeout(function(){
        var locations = <?php print_r(json_encode($locations)) ?>;
        var organization = <?php print_r(json_encode($organization->organization_name)) ?>;
        var maplocation = <?php print_r(json_encode($map)) ?>;

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

        latitude = locations.length > 0 ? locations[0].location_latitude : null;
        longitude = locations.length > 0 ? locations[0].location_longitude : null ;

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

    $('button.delete-td').on('click', function() {
        var value = $(this).val();
        $('input#organization_recordid').val(value);
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