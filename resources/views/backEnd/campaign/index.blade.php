@extends('layouts.app')
@section('title')
Organizations
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

    .jconfirm-box-container {
        margin-left: 35% !important;
    }
</style>

@section('content')
<div class="wrapper">
    <!-- Page Content Holder -->
    <div class="col-md-2 left_side_menu">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#">Campaigns</a>
                <ul class="nav flex-column">
                    <li><a class="{{Request::segment(1) == 'campaigns' ? 'nav-link active' : 'nav-link'}}"
                            href="{{route('campaigns.index')}}">View All</a></li>
                    <li><a class="nav-link" href="{{route('campaigns.create')}}">Create a Campaign</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('messages.index')}}">Messages</a>
                <ul class="nav flex-column">
                    <li><a class="{{Request::segment(1) == 'messages' ? 'nav-link active' : 'nav-link'}}"
                            href="{{route('messages.index')}}">All</a></li>
                    <li><a class="{{Request::segment(2) == 'sent' ? 'nav-link active' : 'nav-link'}}"
                            href="{{url('/message/sent')}}">Sent</a></li>
                    <li><a class="{{Request::segment(2) == 'recieved' ? 'nav-link active' : 'nav-link'}}"
                            href="{{url('/message/recieved')}}">Received</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="col-md-10">
        <div id="organizations-content">
            <div class="row">
                <div class="col-sm-12" style="margin-top:25px;">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="control-label sel-label-org pl-4 "><b>Type</b></label>
                                <div class="col-md-6 col-sm-6 col-xs-12" id="religion-div">
                                    {!! Form::select('type[]',['Email' => 'Email','SMS' => 'SMS','Audio' =>
                                    'Audio','sms and audio' =>
                                    'sms and audio'],null,['class' =>'form-control
                                    selectpicker','multiple'=>'multiple','data-live-search'=>'true', 'id'=>'type'] ) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label class="control-label sel-label-org pl-4 "><b>Status</b></label>
                                <div class="col-md-6 col-sm-6 col-xs-12" id="religion-div">
                                    {!! Form::select('status',['Draft' => 'Draft','Sent' => 'Sent'],null,['class'
                                    =>'form-control','placeholder' => 'Select Status','id' => 'status'] ) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="control-label sel-label-org pl-4 "><b>Groups</b></label>
                                <div class="col-md-6 col-sm-6 col-xs-12" id="religion-div">
                                    {!! Form::select('group[]',$groupList,null,['class'
                                    =>'form-control selectpicker',
                                    'multiple'=>'multiple','data-live-search'=>'true', 'id'=>'group'] ) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label class="control-label sel-label-org pl-4 ">Send Date</label>
                                <div class="col-md-6 col-sm-6 col-xs-12" id="religion-div">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input id="startDate" name="startDate" type="text"
                                                class="form-control date-range-filter" data-date-format="dd-mm-yyyy"
                                                data-link-format="yyyy-mm-dd" readonly placeholder="Start Date" />
                                        </div>
                                        <div class="col-md-6">
                                            <input id="endDate" name="endDate" type="text"
                                                class="form-control date-range-filter" data-date-format="dd-mm-yyyy"
                                                data-link-format="yyyy-mm-dd" readonly placeholder="End Date" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="control-label sel-label-org pl-4"></label>
                                <div class="col-md-6 col-sm-6 col-xs-12" id="clear-btn-div">
                                    <button class="btn btn-success btn-rounded" id="clear-filter-org-btn">
                                        <i class="fa fa-refresh"></i> Clear Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (session()->has('success'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>
                            {!! session()->get('success') !!}
                        </strong>
                    </div>
                    @endif

                    @if (session()->has('error'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>
                            {!! session()->get('error') !!}
                        </strong>
                    </div>
                    @endif
                </div>
                <div class="col-sm-12 p-20">
                    <table class="table table-striped jambo_table bulk_action nowrap" id="tbl-campaign">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th class="default-inactive">Status</th>
                                <th class="default-active">Last Modified</th>
                                <th class="default-active">Name</th>
                                <th class="default-active">Type</th>
                                <th class="default-active">Groups</th>
                                <th class="default-active">Recipients</th>
                                <th class="default-inactive">Delivered</th>
                                <th class="default-inactive">Responses</th>
                                <th class="default-inactive">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($campaigns) > 0)
                            @foreach ($campaigns as $key => $campaign)
                            @php
                            $delivered = 0;
                            foreach($campaign->report as $value){
                            if ($value->status == 'Delivered' || $value->status == 'sent') {
                            $delivered += 1;
                            }
                            }
                            @endphp
                            <tr id="delete_{{ $campaign->id}}">
                                <td>{{$key + 1}} </td>
                                <td>
                                    <span
                                        class="{{$delivered > 0 ? 'badge badge-success' : 'badge badge-danger'}}">{{$delivered > 0 ? 'Sent' : 'Draft'}}</span>
                                </td>
                                <td>{{date('d-m-Y h:m:s',strtotime($campaign->updated_at))}} </td>
                                <td>
                                    @if ($delivered > 0)
                                    <a href="{{ route('campaign_report',$campaign->id) }}" style="color:#3f51b5">
                                        <u>
                                            {{$campaign->name}}
                                        </u>
                                        <a>
                                            @else
                                            {{$campaign->name}}
                                            @endif

                                </td>
                                <td>
                                    <span
                                        class="{{$campaign->campaign_type == 1 ? ('badge badge-success') : ($campaign->campaign_type == 2 ? 'badge badge-danger' : ($campaign->type == 3 ? 'badge badge-warning' : 'badge badge-primary'))}}">{{$campaign->campaign_type == 1 ? ('Email') : ($campaign->campaign_type == 2 ? 'SMS' : ($campaign->type == 3 ?'Audio' : 'sms and audio')) }}</span>
                                </td>
                                <td>
                                    @php
                                    $group_id = '';
                                    if ($campaign->group_id != '') {
                                    $group_id = explode(',',$campaign->group_id);
                                    }
                                    @endphp
                                    @if ($group_id != '')
                                    @foreach ($group_id as $id)
                                    @foreach ($groups as $group)
                                    @if ($group->id == (int)$id)
                                    <span class="badge badge-primary">{{$group->group_name}}</span>
                                    @endif
                                    @endforeach
                                    @endforeach
                                    @endif
                                </td>
                                <td>
                                    @php
                                    $recipient = explode(',',$campaign->recipient);
                                    @endphp
                                    {{count($recipient)}}
                                </td>
                                <td>
                                    {{ $delivered }}
                                </td>
                                <td>
                                    @php
                                    $response = 0;
                                    foreach($campaign->report as $value){
                                    if ($value->status == 'Incoming') {
                                    $response += 1;
                                    }
                                    }
                                    @endphp
                                    {{ $response }}
                                </td>
                                <td>
                                    @if ($delivered == 0)
                                    <a href="{{route('campaigns.edit',$campaign->id)}}"><i class="fa fa-pencil"
                                            aria-hidden="true"
                                            style="border:none; background:none; color:steelblue;"></i></a>
                                    <button type="submit" onclick="delete_canpaigns({{$campaign->id}})"
                                        style="border:none; background:none; color:steelblue;"><i class="fa fa-trash"
                                            aria-hidden="true"></i></button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="9"><b>No Record Found!</b></td>
                            </tr>
                            @endif
                        <tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection
@section('customScript')
<script src="{{asset('js/markerclusterer.js')}}"></script>
<script type="text/javascript">
    function fun_updateStatus(id)
    {
        var token = $('meta[name="_token"]').attr('content');
        $.confirm({
            title: 'Status update!',
            theme: 'black',
            content: '<span style="font-size: 16px;">Are you sure want to change status?</span>',
            confirmButtonClass: 'btn-primary',
            cancelButtonClass: 'btn-danger',
            confirmButton:'Yes',
            boxWidth: '30%',
            useBootstrap: true,
            cancelButton:'No',
            confirm: function(){
                    $.ajax({
                        type: 'POST',
                        url: "{{route('updateStatus')}}",
                        data: {'_token':token, 'id':id},          
                        success: function(resultData) { 
                            if(resultData.success){
                                if(resultData.status == 0){
                                    $('#id_'+id).removeClass('badge-danger')
                                    $('#id_'+id).addClass('badge-success')
                                    $('#id_'+id).text('Active');
                                    //alert('Successfully update status')
                                    $.confirm({
                                        title: 'Status update successfully!',
                                        theme: 'black',
                                        confirmButtonClass: 'btn-primary',        
                                        confirmButton:'Ok',
                                        boxWidth: '30%',
                                        
                                    });    
                                }else{
                                    $('#id_'+id).removeClass('badge-success')
                                    $('#id_'+id).addClass('badge-danger')
                                    $('#id_'+id).text('Inactive');
                                    //alert('Successfully update status')
                                    $.confirm({
                                        title: 'Status update successfully!',
                                        theme: 'black',
                                        confirmButtonClass: 'btn-primary',        
                                        confirmButton:'Ok',
                                        boxWidth: '30%',
                                        
                                    }); 
                                    
                                }

                            }              
                        }
                    });  
                },
            cancel: function(){
            }
        });
        
    }
    $(function () {
        $('#startDate').datetimepicker({ 
        //   pickTime: false, 
        //   format: "DD-MM-YYYY", 
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
        });
      
        $('#endDate').datetimepicker({ 
        //   pickTime: false, 
        //   format: "DD-MM-YYYY",
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0 
        });

    });
    $(document).ready(function(){
        dataTable = $('#tbl-campaign').DataTable();
        $('select#type').on('change', function() {
            var selectedList = $(this).val();
                search = selectedList.join('|')
                search = search.replace(/\(/g, "\\(")
                search = search.replace(/\)/g, "\\)")
                dataTable
                .column(4)
                .search(search ? search : '', true, false,false).draw();
        });
        $('select#status').on('change', function() {
            var search = $(this).val();
                dataTable
                .column(1)
                .search(search ? search : '', true, false,false).draw();
        });
        $('select#group').on('change', function() {
            var selectedList = $(this).val();
                search = selectedList.join('|')
                search = search.replace(/\(/g, "\\(")
                search = search.replace(/\)/g, "\\)")
                dataTable
                .column(5)
                .search(search ? search : '', true, false,false).draw();
        });
           $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                
            // If min or max are empty or invalid they are ignored
            // if min or max are valid and the date in the column is blank or invalid, its filtered out.
                    
                    var valid = true;
                    var min = moment($("#startDate").val());
                    if (!min.isValid()) { min = null; }
            
                    var max = moment($("#endDate").val());
                    if (!max.isValid()) { max = null; }
            
                    if (min === null && max === null) {
                        // no filter applied or no date columns
                        valid = true;
                    }
                    else {
                    // I coded this to look for columns of type date
                    // you can easily change this to look in specific columns.
                        $.each(settings.aoColumns, function (i, col) {

                            if (col.sTitle == "Last Modified") {
                                var cDate = moment(data[i]);
                                console.log(cDate)
                                if (cDate.isValid()) {
                                    if (max !== null && max.isBefore(cDate)) {
                                        valid = false;
                                    }
                                    if (min !== null && cDate.isBefore(min)) {
                                        valid = false;
                                    }
                                }
                                else {
                                    valid = false;
                                }
                            }
                        });
                    }
                    return valid;
            });
        $('#endDate').change( function() {
            dataTable.draw();
        });

        $('#clear-filter-org-btn').click(function(){
            $('select#type').val([]).change();
            $('select#status').val('').change();
            $('select#group').val([]).change();
            $('#startDate').val('');
            $('#endDate').val('').change();
        });

    })
    function delete_canpaigns(id){
        var token = $('meta[name="_token"]').attr('content');
        $.confirm({
                title: 'Campaigns Delete!',
                theme: 'black',
                content: '<span style="font-size: 16px;">Are you sure want to delete this record?</span>',
                confirmButtonClass: 'btn-primary',
                cancelButtonClass: 'btn-danger',
                confirmButton:'Yes',
                boxWidth: '30%',
                useBootstrap: false,
                cancelButton:'No',
                confirm: function(){
                         $.ajax({
                            type: 'POST',
                            url: "{{route('deleteCampaigns')}}",
                            data: {'_token':token, 'id':id },          
                            success: function(resultData) { 
                                if(resultData.success){
                                    $('#delete_'+id).hide();
                                    //alert('successfully deleted.');
                                    $.confirm({
                                        title: 'Campaigns deleted successfully!',
                                        theme: 'black',                                        
                                        confirmButtonClass: 'btn-success',        
                                        confirmButton:'Ok',
                                        boxWidth: '20%',
                                    });    
                                }else{
                                    $.confirm({
                                        title: 'Not deleted!',
                                        theme: 'black',                                        
                                        confirmButtonClass: 'btn-success',        
                                        confirmButton:'Ok',
                                        boxWidth: '20%',
                                    }); 
                                }                    
                            }
                        }); 
                    },
                cancel: function(){
                }
            });
    }
</script>
@endsection