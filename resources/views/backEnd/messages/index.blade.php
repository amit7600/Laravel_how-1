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

    button[data-id="campaign"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="borough"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    .sel-label-org {
        width: 20%;
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
    <div class="col-md-2 left_side_menu">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{route('campaigns.index')}}">Campaigns</a>
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
                            href="{{url('/message/recieved')}}">Incoming</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('messagesSetting')}}">Setting</a>
            </li>
        </ul>
    </div>
    <div class="col-md-10">
        <div id="organizations-content">
            <div class="row">
                <div class="col-sm-12" style="margin-top:25px;">
                    <div class="form-group row">
                        <div class="col-md-5">
                            <div class="row">
                                <label class="control-label sel-label-org pl-4 ">Type</label>
                                <div class="col-md-6 col-sm-6 col-xs-12" id="religion-div">

                                    {!! Form::select('type[]',['Email' => 'Email','SMS' => 'SMS','Audio' =>
                                    'Audio','sms and audio' =>
                                    'sms and audio'],null,['class' =>'form-control
                                    selectpicker','multiple'=>'multiple','data-live-search'=>'true', 'id'=>'type'] ) !!}

                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <label class="control-label sel-label-org pl-4 ">Status</label>
                                <div class="col-md-6 col-sm-6 col-xs-12" id="religion-div">
                                    {!! Form::select('status[]',['Undelivered' => 'Undelivered','Delivered' =>
                                    'Delivered','Incoming' =>
                                    'Incoming'],null,['class' =>'form-control
                                    selectpicker','multiple'=>'multiple','data-live-search'=>'true', 'id'=>'status'] )
                                    !!}

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-5">
                            <div class="row">
                                <label class="control-label sel-label-org pl-4 ">Direction</label>
                                <div class="col-md-6 col-sm-6 col-xs-12" id="religion-div">
                                    {!! Form::select('direction',['outbound-api' => 'Outbound Api','Inbound-api' =>
                                    'Inbound-api'],null,['class'
                                    =>'form-control','placeholder' => 'Select Direction','id' => 'direction'] ) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <label class="control-label sel-label-org pl-4 ">Date/Time Picker</label>
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
                        <div class="col-md-5">
                            <div class="row">
                                <label class="control-label sel-label-org pl-4 ">Campaign</label>
                                <div class="col-md-6 col-sm-6 col-xs-12" id="religion-div">
                                    {!! Form::select('campaign[]',$campaign_name,null,['class' =>'form-control
                                    selectpicker','multiple'=>'multiple','data-live-search'=>'true',
                                    'id'=>'campaign'])!!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-5">
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
                </div>
                <div class="col-md-4">
                    <button class="btn btn-danger" data-toggle="modal" data-target="#campaignModal">Connect to
                        Campaign</button>
                    <button class="btn btn-primary" onclick="openGroup()">Connect to
                        Group</button>
                </div>
                <div class="col-sm-12 p-20">
                    <div id="error"></div>
                    <div class="table-responsive">
                        <table class="table table-striped jambo_table bulk_action nowrap" id="tbl-message">
                            <thead>
                                <tr>
                                    <th><b><input type="checkbox" id="select-all" name="checkall"></b></th>
                                    <th>ID</th>
                                    <th class="default-inactive">Type</th>
                                    <th class="default-inactive">Campaign name</th>
                                    <th class="default-active">Direction</th>
                                    <th class="default-active">Status</th>
                                    <th class="default-active">Body</th>
                                    <th class="default-active">From</th>
                                    <th class="default-active">To</th>
                                    <th class="default-inactive">Date Sent</th>
                                    <!-- <th class="default-inactive">Response to (Campaign)</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @if($getCampaignReport)
                                @foreach($getCampaignReport as $key=>$value)
                                <tr>
                                    @if ($value->status == 'Incoming')
                                    <td><b><input type="checkbox" name="checkUncheck[]" id="checkAllAuto"
                                                value="{{ $value->id}}" class="checkAllAuto"></b>
                                    </td>
                                    @else
                                    <td></td>
                                    @endif

                                    <td>{{ $key+1}}</td>
                                    <td><span
                                            class="badge badge-{{$value->type ==1 ? 'success' : ($value->type == 2 ? 'danger' : ($value->type == 3 ?'warning' : 'primary'))}}">{{$value->type ==1 ? 'Email' : ($value->type == 2 ? 'SMS' : ($value->type == 3 ?'Audio' : 'sms and audio'))}}</span>
                                    </td>
                                    <td>{{$value->campaign != null ? $value->campaign->name : ''}} </td>
                                    <td>{{ $value->direction }}</td>
                                    <td>{{ $value->status}}
                                    </td>
                                    <td>{{$value->body}}</td>
                                    <td>{{$value->fromNumber}}</td>
                                    <td>{{$value->toNumber}}</td>
                                    <td>{{date('d-m-Y h:m:s',strtotime($value->date_sent))}}</td>
                                    <!-- <td>
                                        @if ($value->status == 'Incoming')
                                        {!! Form::select('campaignData',$campaignDetail,$value->campaign_id,['class'
                                        =>'form-control','id' => 'campaignData','placeholder'=>'Select campaign'])!!}
                                        @endif
                                    </td> -->
                                </tr>
                                @endforeach
                                @endif
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div id="campaignModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Campaign</h4>
            </div>
            <div class="modal-body">
                <label class="control-label ">Select Campaign</label>
                {!! Form::select('selectCampaign',$campaignDetail,null,['class'
                =>'form-control','id' => 'selectCampaign','placeholder'=>'Select campaign'])!!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveCampaign">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
            <div class="modal-header">
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

@endsection
@section('customScript')

<script type="text/javascript"
    src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>
<script src="{{asset('js/markerclusterer.js')}}"></script>
<script type="text/javascript">
    $(function () {
        $('#startDate').datetimepicker({ 
        //   pickTime: false, 
        format: "dd-mm-yyyy", 
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
        format: "dd-mm-yyyy", 
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0 
        });

    });
    $(document).on('change','#select-all',function(e){
            
        if($(this).is(":checked")) {
            $('.checkAllAuto').prop('checked',true);
        }else{
            $('.checkAllAuto').prop('checked',false);
        }
    }); 
    $(document).on('change','#groupAllCheck',function(e){
            
        if($(this).is(":checked")) {
            $('.groupAllCheck').prop('checked',true);
        }else{
            $('.groupAllCheck').prop('checked',false);
        }
    });

    var dataTable;
    var checked_terms_set;
    var newContactData = [];
    $(document).ready(function(){
        
        //dataTable = $('#tbl-message').DataTable();
        dataTable = $('#tbl-message').DataTable({
            // "processing": true,
            // "serverSide": true,
            // "ajax": "/",
            "columnDefs": [
                {
                    'targets': 0,
                    'bSortable': false,
                }
            ],
            // 'select': {
            //     'style': 'multi'
            // },
        }); 

        $('select#type').on('change', function() {
            var selectedList = $(this).val();
                search = selectedList.join('|')
                search = search.replace(/\(/g, "\\(")
                search = search.replace(/\)/g, "\\)")
                
                dataTable
                .column(2)
                .search(search ? search : '',true, false,false).draw();
        });
        $('select#campaign').on('change', function() {
            var selectedList = $(this).val();
                search = selectedList.join('|')
                search = search.replace(/\(/g, "\\(")
                search = search.replace(/\)/g, "\\)")
                dataTable
                .column(3)
                .search(search ? search : '', true, false).draw();
        });
        $('select#status').on('change', function() {
            var selectedList = $(this).val();
                search = selectedList.join('|')
                search = search.replace(/\(/g, "\\(")
                search = search.replace(/\)/g, "\\)")
                dataTable
                .column(5)
                .search(search ? search : '', true, false,false).draw();
        });
        $('select#direction').on('change', function() {
            var search = $(this).val();
            //     search = selectedList.join('|')
            //     search = search.replace(/\(/g, "\\(")
            //     search = search.replace(/\)/g, "\\)")
                dataTable
                .column(4)
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
                            if (col.sTitle == "Date Sent") {
                                var cDate = moment(data[i]);
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
            $('select#campaign').val([]).change();
            $('#startDate').val('');
            $('#endDate').val('').change();
        });

        
        var campaignId
        var groupId
        
        $('#saveCampaign').click(function(){
            var id = []
            var checkbox = $('#tbl-message').find('input[type="checkbox"]:checked')
            checkbox.each(function(index,data){
                id.push(data.value)
            })
            campaignId = $('#selectCampaign').val();
            if(campaignId == ''){
                alert('Please select campaign first.');
                return false;
            }
            connect_campaign(id,campaignId)
        })
        function connect_campaign(id,campaignId){
            
            if($.inArray('on', id) != -1){
                id.splice(id.indexOf('on'),1)
            }
            if(id.length == 0){
                alert('please select any report in table.')
                return false
            }
            $('#loading').show();
            $.ajax({
                method: 'POST',
                url: '{{route("connect_compaign")}}',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                data:{ id, campaignId},
                success:function(response){
                    $('#loading').hide();
                    window.location.reload();
                },
                error:function(error){
                    $('#loading').hide();
                    $('#message').empty();
                    $('#addClass').addClass('bg-danger');
                    $('#message').append('<h4 style="color:#fff;">'+error['responseJSON'].message+'</h4>')
                    $('#alertModal').modal('show');

                }
            })
        }

        $('#saveGroup').click(function(){
            var id = []
            var checkbox = $('#groupContact').find('input[type="checkbox"]:checked')
            checkbox.each(function(index,data){
                id.push(data.value)
            })
            if((newContactData.length == 0 && id.length == 0)){
                $('#message').empty();
                $('#addClass').addClass('bg-danger');
                $('#message').append('<h4 style="color:#fff;">Please select any contact first.</h4>')
                $('#alertModal').modal('show');
                return false;
            }
            groupId = $('#selectGroup').val();
            if(groupId == ''){
                $('#message').empty();
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
            $('#loading').show();
            $.ajax({
                method: 'POST',
                url: '{{route("connect_group")}}',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                data:{ id, groupId,newContactData},
                success:function(response){
                    $('#loading').hide();
                    $('#message').empty();
                    $('#addClass').addClass('bg-success');
                    $('#message').append('<h4 style="color:#fff;">'+response.message+'</h4>')
                    $('#alertModal').modal('show');
                    setTimeout(function(){
                         window.location.reload();
                    }, 3000);
                    
                },
                error:function(error){
                    $('#loading').hide();
                    $('#message').empty();
                    $('#addClass').addClass('bg-danger');
                    $('#message').append('<h4 style="color:#fff;">'+error['responseJSON'].message+'</h4>')
                    $('#alertModal').modal('show');
                }
            })
        } 
})
    function openGroup(){
        $('#groupContact').empty();
        $('#error').empty();
        var id = []
        var checkbox = $('#tbl-message').find('input[type="checkbox"]:checked')
        checkbox.each(function(index,data){
            id.push(data.value)
        })
        if($.inArray('on', id) != -1){
            id.splice(id.indexOf('on'),1)
        }
        if(id.length == 0){
            $('#message').empty();
            $('#addClass').addClass('bg-danger');
            $('#message').append('<h4 style="color:#fff;">Please select any report first!</h4>')
            $('#alertModal').modal('show');
            return false;
        }
        
        $('#loading').show();
        $.ajax({
            method: 'POST',
            url: '{{route("getContact")}}',
            headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            data:{ id },
            success:function(response){
                $('#loading').hide();
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
                $('#loading').hide();
                $('#message').empty();
                $('#addClass').addClass('bg-danger');
                $('#message').append('<h4 style="color:#fff;">'+error['responseJSON'].message+'</h4>')
                $('#alertModal').modal('show');
            }
        })
    }

    function createGroup(){
        let group_name = $('#group_name').val();
        $('#loading').show();
        $.ajax({
            method: 'POST',
            url: '{{route("create_group")}}',
            headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            data:{ group_name },
            success:function(response){
                $('#createGroup').modal('hide');
                $('#loading').hide();
            },
            error:function(error){
                $('#createGroup').modal('hide');
                $('#loading').hide();
                $('#message').empty();
                $('#addClass').addClass('bg-danger');
                $('#message').append('<h4 style="color:#fff;">'+error['responseJSON'].message+'</h4>')
                $('#alertModal').modal('show');
            }
        })
    }
    
</script>
@endsection