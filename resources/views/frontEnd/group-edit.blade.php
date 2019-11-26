@extends('layouts.app')
@section('title')
Group Edit
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">   
    
    #groups-edit-content {
        margin-top: 50px;
        width: 35%;
    }
    
    #groups-edit-content .form-group {
        width: 100%;
    }

    button[data-id="group_type"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    .form-group button {
        width: 32.96%;
    }

    .form-group a {
        width: 32.96%;
    }

    @media only screen and (max-width: 768px) {
        .form-group button {
            width: 100%;
        }
        .form-group a {
            width: 32.96%;
        }
    }
    .group-details-div.org .dropdown.bootstrap-select.form-control {
        padding: 0 15px;
    }
    .delete-btn-div {
        text-align: center;
    }
    #view-group-btn {
        float: right;
    }
    h1 {
        text-align: center;
    }
</style>

@section('content')
<div class="wrapper">
    <div id="groups-edit-content" class="container">
        <h1>Edit Group</h1>
        <div class="form-group delete-btn-div">
            <button class="btn btn-danger delete-td" id="delete-group-btn" value="{{$group->group_recordid}}" data-toggle="modal" data-target=".bs-delete-modal-lg"><i class="fa fa-fw fa-remove"></i>Delete</button>
        </div>
        <form action="/group/{{$group->group_recordid}}/update" method="GET">
            <div class="row">
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Group Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 group-details-div">
                        <input class="form-control selectpicker"  type="text" id="group_name" name="group_name" value="{{$group->group_name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Group Type: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 group-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="group_type" name="group_type">
                            @foreach($group_type_list as $key => $group_type)
                                <option value="{{$group_type}}" @if ($group->group_type == $group_type) selected @endif>{{$group_type}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Group Email: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 group-details-div">
                        <input class="form-control selectpicker"  type="text" id="group_email" name="group_email" value="{{$group->group_emails}}">    
                    </div>
                </div>
               
                <div class="form-group"> 
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-rounded" id="save-group-btn"><i class="fa fa-save"></i>Save</button>
                        <a href="/group/{{$group->group_recordid}}" class="btn btn-success btn-rounded" id="view-group-btn"><i class="fa fa-eye"></i>Close</a>
                    </div>                   
                </div>
            </div>
        </form>
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
                            <h4>Are you sure to delete this group?</h4>
                            
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

<script>  
    $('button.delete-td').on('click', function() {
        var value = $(this).val();
        $('input#group_recordid').val(value);
    });
</script>
@endsection




