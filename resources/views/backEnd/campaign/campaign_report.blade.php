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
                        <p class="card-text" style="display: inline-block; margin-right:20px;"><b>Audio Record:</b>
                        </p>
                        <div id="soundTag" style="display:none;"></div>
                        @if (strstr($campaign->campaign_file,'audio/'))
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
                                $contact = \App\Contact::whereId($value->contact_id)->first();
                                @endphp
                                <td>
                                    @if ($contact)
                                    <a href="/contact/{{$contact->contact_recordid}}">{{ $value->toContact }}</a>
                                    @else
                                    {{ $value->toContact }}
                                    @endif
                                </td>
                                <td> {{ $value->fromNumber }} </td>
                                <td> {{ $value->fromContact }} </td>
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
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Group</h4>
            </div>
            <div class="modal-body">
                <label class="control-label ">Select static group</label>
                {!! Form::select('selectGroup',$GroupDetail,null,['class'
                =>'form-control','id' => 'selectGroup','placeholder'=>'Select Group'])!!}
                <div class="table-responsive">
                    <table class="table table-striped jambo_table bulk_action nowrap datatable" id="group_table">
                        <thead>
                            <th><b><input type="checkbox" name="checkall" id="groupAllCheck"></b></th>
                            <th>Phone</th>
                            <th class="default-inactive">Email</th>
                            <th class="default-inactive">Contact Name</th>
                            <th class="default-active">Contact Organization</th>
                        </thead>
                        <tbody id="groupContact">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveGroup">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<script>
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
            groupId = $('#selectGroup').val();
            if(groupId == ''){
                alert('Please select campaign first.');
                return false;
            }
            connect_group(id,groupId)
        });
        function connect_group(id,groupId){
            if($.inArray('on', id) != -1){
                id.splice(id.indexOf('on'),1)
            }
            if(id.length == 0){
                alert('please select any report in table.')
                return false
            }
            $.ajax({
                method: 'POST',
                url: '{{route("connect_group")}}',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                data:{ id, groupId},
                success:function(response){
                    alert(response.message)
                    window.location.reload();
                }
            })
        }
</script>
<script>
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
        $.ajax({
                method: 'POST',
                url: '{{route("getContact")}}',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                data:{ id},
                success:function(response){
                    
                    if(response.success){
                        $('#groupModal').modal('show');
                        var data = response.data
                        $.each(data,function(i,v){
                            $('#groupContact').append('<tr><td><b><input type="checkbox" name="groupCheck[]" value="'+v.id+'" class="checkAllAuto"></b></td><td>'+v.phone+'</td><td>'+v.email+'</td><td>'+v.name+'</td><td>'+v.organization+'</td></tr>')
                        });
                    }
                },
                error:function(error){
                    $('#error').append('<div class="alert alert-danger">'+error['responseJSON'].message+'</div>')
                }
            })
    }
</script>

@endsection
@section('customScript')
<script src="{{asset('js/markerclusterer.js')}}"></script>

@endsection