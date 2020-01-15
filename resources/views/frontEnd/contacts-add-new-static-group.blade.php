@extends('layouts.app')
@section('title')
Contact Edit
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    #groups-add-content {
        margin-top: 50px;
        width: 35%;
    }

    #groups-add-content .form-group {
        width: 100%;
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



    h1 {
        text-align: center;
    }
</style>

@section('content')
<div class="wrapper">
    <div id="groups-add-content" class="container">
        <h1>Create a New Group with Selected Members</h1>
        {{-- <form action="/contacts/create_new_static_group_add_members" method="GET"> --}}
        {!! Form::open(['route' => 'create_new_static_group_add_members']) !!}
        <div class="row">
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Group Name: </b></label>
                <div class="col-md-12 col-sm-12 col-xs-12 group-details-div">
                    <input class="form-control selectpicker" type="text" id="group_name" name="group_name">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b>Group Email: </b></label>
                <div class="col-md-12 col-sm-12 col-xs-12 group-details-div">
                    <input class="form-control selectpicker" type="text" id="group_email" name="group_email">
                </div>
            </div>
            <input type="hidden" name="checked_contact_terms" value="{{$checked_terms}}">
            <div class="form-group">
                <div class="col-md-12">
                    <a href="/contacts" class="btn btn-info btn-rounded" id="view-contact-btn"><i
                            class="fa fa-arrow-left"></i> Back</a>
                    <button type="submit" class="btn btn-success btn-rounded" id="save-contact-btn"><i
                            class="fa fa-check"></i>
                        Save</button>
                </div>
            </div>
        </div>
        {{-- </form> --}}
        {!! Form::close() !!}
    </div>
</div>

<script>

</script>
@endsection