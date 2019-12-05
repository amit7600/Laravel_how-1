@extends('layouts.app')
@section('title')
Groups
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


<style type="text/css">
.table a{
    text-decoration:none !important;
    color: rgba(40,53,147,.9);
    white-space: normal;
}
button[data-id="type"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="tag"] {
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
.dataTables_scroll {
    width: 100%;
}
</style>

@section('content')
<div class="wrapper">
    
    <div id="groups-content" class="container">
        <div class="row">
            <div class="col-sm-8 p-20" style="height: 150px;"> 
                <div class="form-group row">
                    <label class="control-label sel-label-org pl-4">Type: </label>
                    <div class="col-md-6 col-sm-6 col-xs-12" id="type-div">
                        <select class="form-control selectpicker"  multiple data-live-search="true" id="type" name="type">
                        @foreach($type_list as $key => $type)
                            <option value="{{$type}}">{{$type}}</option>
                        @endforeach
                        </select>
                    </div>
                </div> 
                <div class="form-group row">
                    <label class="control-label sel-label-org pl-4">Tag: </label>
                    <div class="col-md-6 col-sm-6 col-xs-12" id="tag-div">
                        <select class="form-control selectpicker" data-live-search="true" id="tag" name="tag">
                            <option style="visibility: hidden;" value="">Nothing selected</option>
                        @foreach($tag_list as $key => $tag)
                            <option value="{{$tag}}">{{$tag}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="control-label sel-label-org pl-4"></label>
                    <div class="col-md-6 col-sm-6 col-xs-12" id="clear-btn-div">
                        <button class="btn btn-success btn-rounded" id="clear-filter-group-btn"><i class="fa fa-refresh"></i> Clear Filters</button>
                    </div>
                </div>    
            </div> 

            <div class="col-sm-12 p-20"> 
                <table class="table table-striped jambo_table bulk_action nowrap" id="tbl-group">
                    <thead>
                        <tr>
                            <th class="default-active"></th>
                            <th class="default-active"></th>
                            <th class="default-inactive">Id</th>
                            <th class="default-active">Name</th>
                            <th class="default-active">Type</th>
                            <th class="default-active">Created (date/time)</th>
                            <th class="default-active">Members</th>
                            <th class="default-active">Message Last Sent</th>
                            <th class="default-active">Time Last Sent</th>
                            <th class="default-active">Total Messages Sent</th>
                            <th class="default-inactive">Tag</th>                           
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($groups as $key => $group)
                        <tr>
                            <td>
                                <a class="btn btn-primary open-td" href="/group/{{$group->group_recordid}}" style="color: white;">Open</a>
                            </td>
                            <td>
                                <button class="btn btn-danger delete-td" value="{{$group->group_recordid}}" data-toggle="modal" data-target=".bs-delete-modal-lg"><i class="fa fa-fw fa-remove"></i>Delete</button>
                            </td>
                            <td>{{$group->group_recordid}}</td>
                            <td>{{$group->group_name}}</td>
                            <td>{{$group->group_type}}</td>
                            <td>{{$group->group_created_at}}</td>
                            <td>{{$group->group_members}}</td>
                            <td>{{$group->group_message_last_sent}}</td>
                            <td>{{$group->group_last_modified}}</td>
                            <td>15</td>
                            <td>{{$group->group_tag}}</td>                            
                        </tr>
                    @endforeach
                    <tbody>
                </table>
            </div>
            
        </div>
        <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="/group_delete_filter" method="POST" id="group_delete_filter">
                        {!! Form::token() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Delete Group</h4>
                        </div>
                        <div class="modal-body">
                            
                            <input type="hidden" id="group_recordid" name="group_recordid">
                            <h4>Are you sure delete this group?</h4>
                            
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
<script>
    var dataTable;
    $(document).ready(function() {
        dataTable = $('#tbl-group').DataTable({
            "scrollX": true,
            dom: 'lBfrtip',
            buttons: [{
                extend: 'colvis',
                columns: [8]
            }],
            columnDefs: [
                { targets: 'default-inactive', visible: false},
            ]
        });
    });
   
    $('select#type').on('change', function() {
        
        var selectedList = $(this).val();
        search = selectedList.join('|')
        dataTable
            .column(4)
            .search(search ? search : '', true, false).draw();
    });
    $('select#tag').on('change', function() {
        
        var selectedList = $(this).val();
        $('input#tag_list').val(selectedList);
        search = selectedList
        dataTable
            .column(10)
            .search(search ? search : '', true, false).draw();
    });
    $('button#clear-filter-group-btn').on('click', function(e) {
        e.preventDefault();
        window.location.reload(true);  
    });
    $('button.delete-td').on('click', function() {
        var value = $(this).val();
        $('input#group_recordid').val(value);
    });

</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{$map->api_key}}&libraries=places&callback=initMap"
  async defer>
</script>
@endsection

