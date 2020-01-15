@extends('layouts.app')
@section('title')
Organization Create
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    #organizations-edit-content {
        margin-top: 50px;
        width: 35%;
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
        width: auto;
    }

    .form-group a {
        width: auto;
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
    <div id="organizations-edit-content" class="container">
        <h1>Create New Organization</h1>
        {{-- <form action="/add_new_organization" method="GET"> --}}
        {!! Form::open(['route' => 'organizations.store']) !!}
        <div class="row">

            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Organization Name :</b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <input class="form-control selectpicker" type="text" id="organization_organization_name"
                        name="organization_organization_name" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Organization Type : </b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    {{-- <select class="form-control selectpicker" data-live-search="true"
                            id="organization_organization_type" name="organization_organization_type"> --}}
                    {{-- @foreach($organization_type_list as $key => $org_type)                                
                                <option value="{{$org_type}}">{{$org_type}}</option>
                    @endforeach --}}
                    {!! Form::select('organization_organization_type',$organization_type_list,null,['class' =>
                    'form-control selectpicker','data-live-search' => 'true','id' =>
                    'organization_organization_type']) !!}
                    {{-- </select> --}}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Organization ID :</b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <input class="form-control selectpicker" type="text" id="organization_organization_id"
                        name="organization_organization_id" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Religion : </b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    {{-- <select class="form-control selectpicker" data-live-search="true"
                            id="organization_organization_religion" name="organization_organization_religion">
                            @foreach($organization_religion_list as $key => $org_religion)
                            <option value="{{$org_religion}}">{{$org_religion}}</option>
                    @endforeach
                    </select> --}}
                    {!! Form::select('organization_organization_religion',$organization_religion_list,null,['class'
                    =>
                    'form-control selectpicker','data-live-search' => 'true','id' =>
                    'organization_organization_religion']) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Faith Tradition :</b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    {{-- <select class="form-control selectpicker" data-live-search="true"
                            id="organization_organization_faith_tradition"
                            name="organization_organization_faith_tradition">
                            @foreach($organization_faith_tradition_list as $key => $org_faith_tradition)
                            <option value="{{$org_faith_tradition}}">{{$org_faith_tradition}}</option>
                    @endforeach
                    </select> --}}
                    {!!
                    Form::select('organization_organization_faith_tradition',$organization_faith_tradition_list,null,['class'
                    =>
                    'form-control selectpicker','data-live-search' => 'true','id' =>
                    'organization_organization_faith_tradition']) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Denomination : </b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    {{-- <select class="form-control selectpicker" data-live-search="true"
                            id="organization_organization_denomination" name="organization_organization_denomination">
                            @foreach($organization_denomination_list as $key => $org_denomination)
                            <option value="{{$org_denomination}}">{{$org_denomination}}</option>
                    @endforeach
                    </select> --}}
                    {!!
                    Form::select('organization_organization_denomination',$organization_denomination_list,null,['class'
                    =>
                    'form-control selectpicker','data-live-search' => 'true','id' =>
                    'organization_organization_denomination']) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Judicatory Body : </b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    {{-- <select class="form-control selectpicker" data-live-search="true"
                            id="organization_organization_judicatory_body"
                            name="organization_organization_judicatory_body">
                            @foreach($organization_judicatory_body_list as $key => $org_judicatory_body)
                            <option value="{{$org_judicatory_body}}">{{$org_judicatory_body}}</option>
                    @endforeach
                    </select> --}}
                    {!!
                    Form::select('organization_organization_judicatory_body'
                    ,$organization_judicatory_body_list,null,['class' => 'form-control
                    selectpicker','data-live-search' => 'true','id' => 'organization_organization_judicatory_body'])
                    !!}
                </div>
            </div>
            {{-- <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Facility Address: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                        <input class="form-control selectpicker"  type="text" id="organization_organization_facility_address" name="organization_organization_facility_address" value="">
                    </div>
                </div> --}}
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Address : </b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <input class="form-control selectpicker" type="text" id="address" name="address" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> City : </b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <input class="form-control selectpicker" type="text" id="city" name="city" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> State : </b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <input class="form-control selectpicker" type="text" id="state" name="state" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Zipcode : </b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <input class="form-control selectpicker" type="text" id="zipcode" name="zipcode" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Website :</b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <input class="form-control selectpicker" type="text" id="organization_organization_website"
                        name="organization_organization_website" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Facebook : </b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <input class="form-control selectpicker" type="text" id="organization_organization_facebook"
                        name="organization_organization_facebook" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> # C. Board :</b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <input class="form-control selectpicker" type="text" id="organization_organization_c_board"
                        name="organization_organization_c_board" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Internet Access :</b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <input type="checkbox" id="organization_organization_internete_access"
                        name="organization_organization_internete_access" value="1">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Comments :</b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <input class="form-control selectpicker" type="text" rows="10" cols="30"
                        id="organization_organization_comments" name="organization_organization_comments" value="">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12 text-center">

                    <a href="/" class="btn btn-info btn-rounded" id="view-organization-btn"><i
                            class="fa fa-arrow-left"></i> Close</a>

                    <button type="submit" class="btn btn-success btn-rounded" id="save-organization-btn"><i
                            class="fa fa-check"></i> Save</button>
                </div>
            </div>
        </div>
        {{-- </form> --}}
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(document).ready(function() {
        $('select#organization_organization_type').val([]).change();
        $('select#organization_organization_religion').val([]).change();
        $('select#organization_organization_faith_tradition').val([]).change();
        $('select#organization_organization_denomination').val([]).change();
        $('select#organization_organization_judicatory_body').val([]).change();
    });

</script>
@endsection