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
        <h1>Add New Group</h1>     
        <form action="/add_new_group" method="GET">
            <div class="row">
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Group Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 group-details-div">
                        <input class="form-control selectpicker"  type="text" id="group_name" name="group_name" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Group Type: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 group-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="group_type" name="group_type">
                            @foreach($group_type_list as $key => $group_type)
                                <option value="{{$group_type}}">{{$group_type}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Group Email: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 group-details-div">
                        <input class="form-control selectpicker"  type="text" id="group_email" name="group_email" value="">    
                    </div>
                </div>
               
                <div class="form-group"> 
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-rounded" id="save-group-btn"><i class="fa fa-save"></i>Save</button>
                        <a href="/groups}}" class="btn btn-success btn-rounded" id="view-group-btn"><i class="fa fa-eye"></i>Close</a>
                    </div>                   
                </div>
            </div>
        </form>
    </div>
</div>

<script> 
    $(document).ready(function() {
        $('select#group_type').val([]).change();
    });
</script>
@endsection




