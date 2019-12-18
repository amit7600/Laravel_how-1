@extends('layouts.app')
@section('title')
Organizations
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


<style type="text/css">
    #organizations-edit-content {
        margin-top: 50px;
        width: 50%;
        margin: 0 auto;
    }

    #organizations-edit-content .form-group {
        width: 100%;
    }

    button[data-id="organization_organization_type"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="organization_organization_religion"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="organization_organization_faith_tradition"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="organization_organization_denomination"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="organization_organization_judicatory_body"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    .form-group button {
        width: 32.8%;
    }

    .form-group a {
        width: 32.8%;
    }

    @media only screen and (max-width: 768px) {
        .form-group button {
            width: 100%;
        }

        .form-group a {
            width: 32.96%;
        }
    }

    .organization-details-div.org .dropdown.bootstrap-select.form-control {
        padding: 0 15px;
    }

    .delete-btn-div {
        text-align: center;
    }

    h1 {
        text-align: center;
    }
</style>

@section('content')
<div class="wrapper">
    <!-- Page Content Holder -->
    <div class="col-sm-12">
        <h1>Campaign Report</h1>
        <div class="row">
            <div class="col-md-3 mt-4"></div>
            <div class="col-md-3 mt-4">
                <div class="card background_gry">
                    <div class="card-body">
                        <p class="card-text"><b>Name: {{ $campaign->name }}</b> </p>
                        <p class="card-text ">
                            <b>Status:<span class="badge {{$delivered > 0 ? 'badge-success' : 'badge-danger'}} ">
                                    {{$delivered > 0 ? 'Sent' : 'Draft'}}</span>
                            </b>
                        </p>
                        <p class=" card-text">
                            <b>Type: </b><span
                                class="badge badge-danger">{{$campaign->campaign_type == 1 ? ('Email') : ($campaign->campaign_type == 2 ? 'SMS' : 'Audio') }}</span>
                        </p>
                        <p class="card-text"><b>Created Date: {{ $campaign->created_at->format('m/d/Y') }}</b> </p>
                        <p class="card-text"><b>Send Date: {{ $campaign->created_at->format('m/d/Y') }}</b> </p>
                        {{-- <p class="card-text"><b>#Recipients: @php
                                                    $recipient = explode(',',$campaign->recipient);
                                                    @endphp
                                                    {{count($recipient)}}
                        </b> </p> --}}

                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-4">
                <div class="card background_gry">
                    <div class="card-body">
                        <p class="card-text"><b>Sent By: {{$user->first_name . ' '. $user->last_name}} </b> </p>
                        <p class="card-text"><b>Subject: {{ $campaign->subject }}</b> </p>
                        <p class="card-text"><b>Body: {{ $campaign->body }}</b> </p>
                        <p class="card-text" style="display: inline-block; margin-right:20px;">
                            <b>{!! $campaign->campaign_type == 1 ? '<a href="/download_attachment/'.$campaign->id.'"
                                    target="_blank">Attachment</a>' :
                                'Audio Record:'
                                !!}</b>
                        </p>
                        <div id="soundTag" style="display:none;"></div>
                        @php
                        $filename = public_path($campaign->campaign_file);
                        $file_type = \File::extension($filename);
                        @endphp
                        @if ($campaign->campaign_type == 1)

                        @else
                        @if (strstr($campaign->campaign_file,'audio/') || $file_type == 'mp3' || $file_type == 'mpeg' ||
                        $file_type == 'mpga' || $file_type == 'wav' || $file_type == 'aac')
                        <div class="btn-group" aria-label="Basic example" role="group">
                            <button type="button" class="btn btn-icon btn-primary waves-effect waves-classic"
                                id="button_play"><i class="icon md-play" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-icon btn-primary waves-effect waves-classic"
                                id="button_pause"><i class="icon md-pause" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-icon btn-primary waves-effect waves-classic"
                                id="button_stop"><i class="icon md-stop" aria-hidden="true"></i></button>
                        </div>
                        @else
                        @if ( $campaign->campaign_file != '')

                        <img src="{{ $campaign->campaign_file }}" alt="" style="width: 70%; border-radius: 100%;">
                        @endif
                        @endif
                        @endif



                        <p class="card-text"><b>Last Modified: {{ $campaign->updated_at->format('m/d/Y') }}</b> </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <div class="top_data text-center" style="border-bottom:1px solid #ddd;">
                        <ul>
                            <li><b>Recipients: @php
                                    $recipient = explode(',',$campaign->recipient);
                                    @endphp
                                    {{count($recipient)}} </b>
                                <span>|</span>

                                <b>Delivered: {{$delivered}} </b>
                                <span>|</span>
                                <b>Responses: {{$response}} </b>
                            </li>
                        </ul>
                    </div>
                    <div class="top_data text-center mt-4">
                        <ul>
                            <li>
                                <button class="btn btn-primary" onclick="openGroup()">Connect to
                                    Group</button>
                                <b>Type: </b>
                                {!! Form::select('type[]',['Email' => 'Email','SMS' => 'SMS','Audio' =>
                                'Audio','SMS + Audio' =>
                                'SMS + Audio'],null,['class' =>'form-control
                                selectpicker','multiple'=>'multiple','data-live-search'=>'true', 'id'=>'type'] ) !!}
                                <span>|</span>
                                <b>Status: </b>
                                {!! Form::select('status[]',['Undelivered' => 'Undelivered','Delivered' =>
                                'Delivered','Incoming' =>
                                'Incoming','sent' =>
                                'sent'],null,['class' =>'form-control
                                selectpicker','multiple'=>'multiple','data-live-search'=>'true', 'id'=>'status'] )
                                !!}
                                <span>|</span>
                                <b>Direction: </b>
                                {!! Form::select('direction',['outbound-api' => 'Outbound Api','Inbound-api' =>
                                'Inbound-api'],null,['class'
                                =>'form-control','placeholder' => 'Select Direction','id' => 'direction'] ) !!}
                            </li>
                        </ul>
                    </div>
                    <div id="error"></div>
                    <table class="table" id="tbl-campaignReport">
                        <thead>
                            <tr>
                                <th><b><input type="checkbox" id="select-all" name="checkall"></b></th>
                                <th><b>Status</b></th>
                                <th><b>Type</b></th>
                                <th><b>Direction</b></th>
                                <th><b>Date Sent</b></th>
                                <th><b>To Number</b></th>
                                <th><b>To Contact</b></th>
                                <th><b>From Number</b></th>
                                <th><b>From Contact</b></th>
                                <th><b>Message Body</b></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($campaign_data as $key => $value)
                            <tr style="font-size:14px;">
                                @if ($value->status == 'Incoming')
                                <td><b><input type="checkbox" name="checkUncheck[]" id="checkAllAuto"
                                            value="{{ $value->id}}" class="checkAllAuto"></b>
                                </td>
                                @else
                                <td></td>
                                @endif
                                <td>{{ $value->status }} </td>
                                <td><span
                                        class="{{$value->campaign->campaign_type == 1 ? ('badge badge-success') : ($value->campaign->campaign_type == 2 ? 'badge badge-danger' : 'badge badge-warning')}}">{{$value->campaign->campaign_type == 1 ? 'Email' : ($value->campaign->campaign_type == 2 ? 'SMS' : 'Audio') }}</span>
                                </td>
                                <td> {{ $value->direction }} </td>
                                <td> {{ $value->date_sent }} </td>
                                <td> {{ $value->toNumber }} </td>
                                @php
                                print_r($value->contact_id);
                                $contact = \App\Contact::whereId($value->contact_id)->first();
                                @endphp
                                <td>
                                    @if ($contact && $value->toContact != 'HowCalm')
                                    <a href="/contact/{{$contact->contact_recordid}}">{{ $value->toContact }}</a>
                                    @else
                                    {{ $value->toContact }}
                                    @endif
                                </td>
                                <td> {{ $value->fromNumber }} </td>
                                <td>
                                    @if ($contact && $value->fromContact != 'HowCalm')
                                    <a href="/contact/{{$contact->contact_recordid}}">{{ $value->fromContact }}</a>
                                    @else
                                    {{ $value->fromContact }}
                                    @endif

                                </td>
                                <td> {{ $value->body }} </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="groupModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg_header">
                <h4 class="modal-title">Group</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 text-right">
                    <button class="btn btn-info" data-toggle="modal" data-target="#createGroup">Create group</button>
                </div>
                <div class="row">
                    <label class="control-label col-md-3"><b>Select static group</b></label>
                    {!! Form::select('selectGroup',$GroupDetail,null,['class'
                    =>'form-control col-md-6','id' => 'selectGroup','placeholder'=>'Select Group'])!!}
                    <div class="table-responsive" id="groupTable" style="display:none;">
                        <div class="table-responsive">
                            <table class="table table-striped jambo_table bulk_action nowrap datatable"
                                id="group_table">
                                <thead>
                                    <th><b><input type="checkbox" name="checkall" id="groupAllCheck"></b></th>
                                    <th>Phone</th>
                                    <th class="default-inactive">Email</th>
                                    <th class="default-inactive">Contact Name</th>
                                    <th class="default-active">Contact Organization</th>
                                </thead>
                                <tbody id="groupContact"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveGroup">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="createGroup" tabindex="-1" role="dialog" aria-labelledby="createGroupLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg_header">
                <h5 class="modal-title" id="createGroupLabel">Create Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <label class="control-label sel-label-org pl-4"><b>Group Name</b></label>
                        <div class="col-md-12 col-sm-12 col-xs-12 group-details-div">
                            <input class="form-control selectpicker" type="text" id="group_name" name="group_name"
                                value="">
                            <span id="groupNameError" style="color:red;display:none;"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="control-label sel-label-org pl-4"><b>Group Name</b></label>
                        <div class="col-md-12 col-sm-12 col-xs-12 group-details-div">
                            {!! Form::select('group_type',['Static' => 'static','Dynamic' =>
                            'dynamic'],'Static',['class'=> 'form-control','id' => 'group_type']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="createGroup()">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade " id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content" id="addClass">
            <div class="modal-header ">
                <h3 class="modal-title" id="exampleModalLongTitle" style="color:#fff">Alert</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="message"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    var newContactData = [];
    $(document).ready(function(){
        dataTable = $('#tbl-campaignReport').DataTable({
            "columnDefs": [
                {
                    'targets': 0,
                    'bSortable': false,
                }
            ],
        });

        $('select#type').on('change', function() {
            var selectedList = $(this).val();
                search = selectedList.join('|')
                search = search.replace(/\(/g, "\\(")
                search = search.replace(/\)/g, "\\)")
                
                dataTable
                .column(1)
                .search(search ? search : '', true, false).draw();
        });
        $('select#status').on('change', function() {
            var selectedList = $(this).val();
                search = selectedList.join('|')
                search = search.replace(/\(/g, "\\(")
                search = search.replace(/\)/g, "\\)")
                dataTable
                .column(0)
                .search(search ? search : '', true, false).draw();
        });
        $('select#direction').on('change', function() {
            var search = $(this).val();
            //     search = selectedList.join('|')
            //     search = search.replace(/\(/g, "\\(")
            //     search = search.replace(/\)/g, "\\)")
                dataTable
                .column(2)
                .search(search ? search : '', true, false,false).draw();
        });
    })
  $(document).on('change','#groupAllCheck',function(e){
            
        if($(this).is(":checked")) {
            $('.checkAllAuto').prop('checked',true);
        }else{
            $('.checkAllAuto').prop('checked',false);
        }
    });



    document.getElementById("soundTag").innerHTML = "<audio controls volume preload='none' src='{{$campaign->campaign_file}}'></audio>";
    
    $('#button_play').on('click', function() {
        $("audio")[0].play();
        
        $('#button_play').addClass('disabled');
        $('#button_pause').removeClass('disabled');
    });
    $('#button_pause').on('click', function() {
        $("audio")[0].pause();
        
        $('#button_pause').addClass('disabled');
        $('#button_play').removeClass('disabled');
    });
    $('#button_stop').on('click', function() {
        $("audio")[0].currentTime = 0;
        $("audio")[0].pause();
        
        $('#button_play').removeClass('disabled');
        $('#button_pause').removeClass('disabled');
    });
    $(document).on('change','#select-all',function(e){
            
        if($(this).is(":checked")) {
            $('.checkAllAuto').prop('checked',true);
        }else{
            $('.checkAllAuto').prop('checked',false);
        }
    });

        $('#saveGroup').click(function(){
            var id = []
            var checkbox = $('#groupContact').find('input[type="checkbox"]:checked')
            checkbox.each(function(index,data){
                id.push(data.value)
            })
            if((newContactData.length == 0 && id.length == 0)){
                $('#message').empty();
                $('#addClass').removeClass('bg-success');
                $('#addClass').removeClass('bg-danger');
                $('#addClass').addClass('bg-danger');
                $('#message').append('<h4 style="color:#fff;">Please select any contact first.</h4>')
                $('#alertModal').modal('show');
                return false;
            }
            groupId = $('#selectGroup').val();
            if(groupId == ''){
                $('#message').empty();
                $('#addClass').removeClass('bg-success');
                $('#addClass').removeClass('bg-danger');
                $('#addClass').addClass('bg-danger');
                $('#message').append('<h4 style="color:#fff;">Please select Group first.</h4>')
                $('#alertModal').modal('show');
                return false;
            }
            connect_group(id,groupId)
        });
        function connect_group(id,groupId){
            if($.inArray('on', id) != -1){
                id.splice(id.indexOf('on'),1)
            }
            $.ajax({
                method: 'POST',
                url: '{{route("connect_group")}}',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                data:{ id, groupId,newContactData},
                success:function(response){
                    $('#message').empty();
                    $('#addClass').removeClass('bg-success');
                    $('#addClass').removeClass('bg-danger');
                    $('#addClass').addClass('bg-success');
                    $('#message').append('<h4 style="color:#fff;">'+response.message+'</h4>')
                    $('#alertModal').modal('show');
                    setTimeout(function(){
                         window.location.reload();
                    }, 3000);
                    
                },
                error:function(error){
                $('#error').append('<div class="alert alert-danger">'+error['responseJSON'].message+'</div>')
                }
            })
        } 
    function openGroup()
        {
            $('#groupContact').empty();
            $('#error').empty();
            var id = []
            var checkbox = $('#tbl-campaignReport').find('input[type="checkbox"]:checked')
            checkbox.each(function(index,data){
                id.push(data.value)
            })
            if($.inArray('on', id) != -1){
                id.splice(id.indexOf('on'),1)
            }
            // if(id.length == 0){
            //     $('#message').empty();
            //     $('#addClass').addClass('bg-danger');
            //     $('#message').append('<h4 style="color:#fff;">Please select any report first!</h4>')
            //     $('#alertModal').modal('show');
            //     return false;
            // }
            
            $('#loading').show();
            $.ajax({
                    method: 'POST',
                    url: '{{route("getContact")}}',
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    data:{ id },
                    success:function(response){
                        $('#groupTable').hide();
                        newContactData = [];
                        if(response.success){
                            var data = response.data
                            const campaignId = response.campaignId
                            const dataLength = parseInt(id.length) - parseInt(campaignId.length)
                            // for store new contact data 
                            $.each(campaignId,function(index,value){
                                newContactData.push(value)
                            });
                            // for append contact details 
                            $.each(data,function(i,v){
                                $('#groupContact').append('<tr><td><b><input type="checkbox" name="groupCheck[]" value="'+v.id+'" class="groupAllCheck"></b></td><td>'+v.phone+'</td><td>'+v.email+'</td><td>'+v.name+'</td><td>'+v.organization+'</td></tr>')
                            });
                            if(data.length != 0 && data.length != id.length ){
                                $('#groupTable').show();
                            }else{
                                $('#groupContact').find('input[type="checkbox"]').prop('checked',true);
                            }
                                $('#groupModal').modal('show');
                        }
                    },
                    error:function(error){
                        $('#error').append('<div class="alert alert-danger">'+error['responseJSON'].message+'</div>')
                    }
                })
        }
    function createGroup(){
        let group_name = $('#group_name').val();
        let group_type = $('#group_type').val();
        $('#groupNameError').hide();
        if(group_name == ''){
            $('#groupNameError').show();
            $('#groupNameError').empty();
            $('#addClass').removeClass('bg-danger');
            $('#addClass').removeClass('bg-success');
            $('#group_name').addClass('alert-danger');
            $('#groupNameError').append('Group name field is required!');
            return false;
        }
        $('#group_name').removeClass('alert-danger');
        $('#loading').show();
        $.ajax({
            method: 'POST',
            url: '{{route("create_group")}}',
            headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            data:{ group_name,group_type },
            success:function(response){
                $('#createGroup').modal('hide');
                $('#loading').hide();
                $('#selectGroup').append('<option selected="selected" value="'+response.data+'">'+group_name+'</option>')
            },
            error:function(error){
                $('#createGroup').modal('hide');
                $('#loading').hide();
                $('#message').empty();
                $('#addClass').removeClass('bg-danger');
                $('#addClass').removeClass('bg-success');
                $('#addClass').addClass('bg-danger');
                $('#message').append('<h4 style="color:#fff;">'+error['responseJSON'].message+'</h4>')
                $('#alertModal').modal('show');
            }
        })
    }
</script>

@endsection
@section('customScript')
<script src="{{asset('js/markerclusterer.js')}}"></script>

@endsection