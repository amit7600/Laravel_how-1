@extends('layouts.app')
@section('title')
Group Profile
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css">

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

    #tbl-group-profile-contact {
        width: 100% !important;
    }

    .card {
        margin-bottom: 1.143rem !important;
    }

    button[data-id="contacts"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    .form-group button {
        width: 32.96%;
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

    table#tbl-group-profile-members {
        width: 100% !important;
        /* display: block; */
        border-bottom: 0px;
    }

    #tbl-group-profile-members_wrapper {
        overflow-x: scroll;
    }
</style>

@section('content')
@if (session()->has('error'))
<div class="alert alert-danger alert-dismissable custom-success-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session()->get('error') }} </strong>
</div>
@endif
@if (session()->has('success'))
<div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session()->get('success') }} </strong>
</div>
@endif
<div class="wrapper">
    <!-- Page Content Holder -->
    <div id="content" class="container">
        <div class="col-md-12">
            <div class="row text-right">
                <div class="col-md-6">
                    <div class="pt-20 text-left btn-download">
                        <form method="GET" action="/group/{{$group->group_recordid}}/tagging" id="group_tagging">
                            <div class="row m-0" id="tagging-div">
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="tokenfield" name="tokenfield"
                                        value="{{$group->group_tag}}" />
                                </div>
                                <div class="col-md-2 p-0">
                                    <button type="submit" class="btn btn-secondary btn-tag-save">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="pt-20 btn-download">
                        <a href="/group/{{$group->group_recordid}}/edit" class="btn btn-primary "><i
                                class="fa fa-fw fa-edit"></i>Edit</a>
                        <button type="button" class="btn btn-secondary " data-toggle="modal"
                            data-target="#addContact"><i class="fa fa-fw fa-edit"></i>Add Contact</button>
                        {{-- <a href="#" class="btn btn-info "><i class="fa fa-fw fa-envelope"></i>Send a Message</a> --}}
                        <button class="btn btn-info" data-toggle="modal" data-target="#sendMessage"><i
                                class="fa fa-fw fa-envelope"></i> Send
                            a Message</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-0">
            <div class="col-md-7 pb-15 pl-30">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">
                            <a href="">{{$group->group_name}}
                            </a>
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Group Type:</b></span>
                            {{$group->group_type}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Created:</b></span>
                            {{$group_date_created}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Group Email:</b></span>
                            {{$group->group_emails}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Group Messages:</b></span>
                            {{$group->group_messages}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Last Sent:</b></span>
                            {{$group->group_message_last_sent}}
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-5 property">
                <div class="card">
                    <div id="map" style="width:initial;margin-top: 0;height: 240px;"></div>
                </div>
            </div>
            <div class="col-md-12 pt-15 pb-15 pl-30 pl-30">
                <div class="card">
                    <div class="card-block">
                        <h3 class="mt-0 mb-20">Members
                            <button class="btn btn-danger remove-td float-right" id="remove-members-group-btn"
                                value="{{$group->group_recordid}}" data-toggle="modal"
                                data-target=".bs-remove-modal-lg"><i class="fa fa-fw fa-remove"></i>Remove</button>
                        </h3>
                        <div class="col-md-12">
                            <table class="table table-striped jambo_table bulk_action nowrap"
                                id="tbl-group-profile-members">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" name="allCheckGroup" id="allCheckGroup">
                                        </th>
                                        <th></th>
                                        <th>ID</th>
                                        <th>First Name</th>
                                        <th>Middle Name</th>
                                        <th>Last Name</th>
                                        <th>Type</th>
                                        <th>Religious Title</th>
                                        <th>Organization</th>
                                        <th>Religion</th>
                                        <th>Borough</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($contacts) > 0)
                                    @foreach($contacts as $key => $contact)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="contactCheck" class="contactCheck"
                                                value="{{ $contact->contact_recordid }}" id="contactCheck">
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm open-td"
                                                href="/contact/{{$contact->contact_recordid}}"
                                                style="color: white;">Open</a>
                                        </td>
                                        <td>{{$contact->contact_recordid}}</td>
                                        <td>{{$contact->contact_first_name}}</td>
                                        <td>{{$contact->contact_middle_name}}</td>
                                        <td>{{$contact->contact_last_name}}</td>
                                        <td>{{$contact->contact_type}}</td>
                                        <td>{{$contact->contact_religious_title}}</td>
                                        <td>
                                            <a id="contact_organization_link"
                                                style="color: #3949ab; text-decoration: underline;"
                                                href="/organization/{{$contact->organization_recordid}}">{{$contact->organization_name}}</a>
                                        </td>
                                        <td>{{$contact->organization_religion}}</td>
                                        <td>{{$contact->address_city}}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                <tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pt-15 pb-15 pl-30 pl-30">
                <div class="card">
                    <div class="card-block">
                        <h3>Campaigns</h3>
                        <div class="table-responsive">
                            <table class="table table-striped jambo_table bulk_action nowrap"
                                id="tbl-group-profile-campagins">
                                <thead>
                                    <tr>
                                        {{-- <th></th> --}}
                                        <th>ID</th>
                                        <th>Campaign Name</th>
                                        <th>Created date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($campaigns as $key => $campaign)
                                    <tr>
                                        {{-- <td></td> --}}
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $campaign->name }}</td>
                                        <td>{{ $campaign->created_at }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade bs-remove-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="/group_remove_members" method="POST" id="group_remove_members">
                            {!! Form::token() !!}
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Remove contacts from Group</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="group_recordid" name="group_recordid">
                                <input type="hidden" id="groupContacts" name="checked_terms">
                                <h4>Are you sure to remove selected members from this group?</h4>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success btn-delete btn-sm">Remove</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bs-message-modal-lg" id="sendMessage" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {!! Form::open(['route' => array('group_message',$group->group_recordid)]) !!}
            {!! csrf_field() !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h2 class="modal-title" id="myModalLabel" style="color: #3949ab;">Send Message</h2>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="contact_message_textarea"><b> Message Type</b></label>
                    {!! Form::select('message_type',['sms' => 'SMS','email' => 'E-mail'],null,['class' =>
                    'form-control','placeholder' => 'Select message type','onchange' =>
                    'messageType(this)']) !!}
                </div>
                <div class="form-group" style="display:none;" id="subject">
                    <label for="contact_message_textarea"><b>subject</b></label>
                    {!! Form::text('subject',null,['class' => 'form-control','placeholder' => 'Subject'])
                    !!}
                </div>
                <div class="form-group">
                    <label for="contact_message_textarea"><b>Message Content</b></label>
                    <textarea class="form-control" id="contact_message_textarea" name="message_body" rows="5"
                        placeholder="Message body"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-info btn-send">Send</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="addContact">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['route' => ['addContactToGroup',$group->group_recordid]]) !!}
            <div class="modal-body">
                <div class="form-group" id="contacts">
                    <label class="control-label sel-label-org pl-4">Select Contacts </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        {!! Form::select('contacts[]',$allContacts,'',['class' =>
                        $errors->has("contacts") ? "form-control selectpicker has-error" :'form-control
                        selectpicker','multiple' =>
                        'multiple','data-live-search'=>'true','id' => 'contacts']) !!}
                        {!! $errors->first('contacts', '<p class="help-block" style="color: red">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection
@section('customScript')
<script type="text/javascript" src="http://sliptree.github.io/bootstrap-tokenfield/dist/bootstrap-tokenfield.js">
</script>
<script type="text/javascript"
    src="http://sliptree.github.io/bootstrap-tokenfield/docs-assets/js/typeahead.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script>
    function messageType(e){
            let value = e.value;
            if(value == 'email'){
                $('#subject').show();
            }else{
                $('#subject').hide();
            }
        }
</script>
<script>
    var dataTable;
    var campaignTable;
    var checked_terms_set;
    $(document).ready(function() {
        dataTable = $('#tbl-group-profile-members').DataTable();
        campaignTable = $('#tbl-group-profile-campagins').DataTable();      
    })
   $(document).ready(function(){  
        
        var locations = <?php print_r(json_encode($locations)) ?>;        
        var maplocation = <?php print_r(json_encode($map)) ?>; 
        console.log(locations,'location');       

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

        latitude = null;
        longitude = null;
        if (locations.length > 0) {
            if (locations[0][0] != null){
            latitude = locations[0][0].location_latitude;
            longitude = locations[0][0].location_longitude;
            } else {
                latitude = null;
                longitude = null;
            }
        }
        
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

        $.each(locations, function(index, value){ 
            if(value[0]){
                mymap.addMarker({
                    lat: value[0].location_latitude,
                    lng: value[0].location_longitude,
                });
            }
        });
        
    });

    $(document).ready(function() {   
        $('#tokenfield').tokenfield({
        autocomplete: {
            delay: 100
        },
        showAutocompleteOnFocus: true
        });
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
    $(document).on('change','#allCheckGroup',function(e){
        if($(this).is(":checked")) {
            $('.contactCheck').prop('checked',true);
        }else{
            $('.contactCheck').prop('checked',false);
        }
    }); 
    $('#remove-members-group-btn').click(function(e){
        var contactId = []
        var checkbox = $('#tbl-group-profile-members').find('input[type="checkbox"]:checked')
        checkbox.each(function(index,data){
            contactId.push(data.value)
        })
        if($.inArray('on', contactId) != -1){
            contactId.splice(contactId.indexOf('on'),1)
        }
        $('#groupContacts').val(contactId)
        var value = $(this).val();
        $('input#group_recordid').val(value);
        // if (!checked_terms_set) {
        //     e.preventDefault();
        //     var value = $(this).val();
        //     $('input#group_recordid').val(value);

        //     var checked_rows = dataTable.rows('.selected').data();
        //     var checked_terms = [];
        //     for (i = 0; i < checked_rows.length; i++) {
        //         checked_terms.push(checked_rows[i][2]);
        //     }            
        //     $('#checked_terms').val(checked_terms.join(","));            
        //     checked_terms_set = true;
        //     $(this).trigger('click');
        // }
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
        $('input#group_recordid').val(value);
    });

</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{$map->api_key}}&libraries=places&callback=initMap" async
    defer>
</script>
@endsection