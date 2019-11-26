@extends('layouts.app')
@section('title')
Facility Create
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">   
    
    #facilities-edit-content {
        margin-top: 50px;
        width: 35%;
    }
    
    #facilities-edit-content .form-group {
        width: 100%;
    }

    button[data-id="facility_location_name"] {
        height: 100%;
        border: 1px solid #ddd;
    } 

    button[data-id="facility_organization_name"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="facility_address_city"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="facility_street_address"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="facility_zip_code"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="facility_building_status"] {
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
    .facility-details-div.org .dropdown.bootstrap-select.form-control {
        padding: 0 15px;
    }
    .delete-btn-div {
        text-align: center;
    }
    #view-facility-btn {
        float: right;
    }
    h1 {
        text-align: center;
    }
</style>

@section('content')
<div class="wrapper">
    <div id="facilities-edit-content" class="container">
        <h1>Add New Facility</h1>
        <form action="/add_new_facility" method="GET">
            <div class="row">  
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 facility-details-div">
                        <input class="form-control selectpicker"  type="text" id="facility_location_name" name="facility_location_name" value="">
                    </div>
                </div> 
                <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Organization: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 facility-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="facility_organization_name" name="facility_organization_name">
                            @foreach($organization_name_list as $key => $org_name)                                
                                <option value="{{$org_name}}">{{$org_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>   
                <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Facility Type: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 facility-details-div">
                        <fieldset id="facility_facility_type" >
                            <input type="radio" value="0" name="facility_facility_type"> Faith-Based-Service Provider<br>
                            <input type="radio" value="1" name="facility_facility_type"> House of Worship<br>
                            <input type="radio" value="2" name="facility_facility_type"> Religious Shool<br>
                            <input type="radio" value="3" name="facility_facility_type"> Other<br>
                        </fieldset> 
                    </div>
                </div> 
                <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Borough: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 facility-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="facility_address_city" name="facility_address_city">
                            @foreach($address_city_list as $key => $address_city)                                
                                <option value="{{$address_city}}">{{$address_city}}</option>
                            @endforeach
                        </select>
                    </div>
                </div> 
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Street Address: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 facility-details-div">
                        <input class="form-control selectpicker"  type="text" id="facility_street_address" name="facility_street_address" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Zip Code: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 facility-details-div">
                        <input class="form-control selectpicker"  type="text" id="facility_zip_code" name="facility_zip_code" value="">
                    </div>
                </div>    
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4"># Congregation of this Facility: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 facility-details-div">
                        <input class="form-control selectpicker"  type="text" id="facility_congregation" name="facility_congregation" value="">
                    </div>
                </div>   
                <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Building Status: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 facility-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="facility_building_status" name="facility_building_status">
                            @foreach($building_status_list as $key => $building_status)                                
                                <option value="{{$building_status}}">{{$building_status}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>  
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Call in Emergency: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 facility-details-div">                     
                        <input type="checkbox" id="facility_facility_call" name="facility_facility_call">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Comments: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 facility-details-div">
                        <input class="form-control selectpicker" type="textarea" rows="10" cols="30" id="facility_location_comments" name="facility_location_comments" value="">
                    </div>
                </div>  
                <div class="form-group"> 
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-rounded" id="save-facility-btn"><i class="fa fa-save"></i>Save</button>
                        <a href="/facilities" class="btn btn-success btn-rounded" id="view-facility-btn"><i class="fa fa-eye"></i>Close</a>
                    </div>                   
                </div>
            </div>
        </form>
    </div>
</div>

<script> 
    $(document).ready(function() {
        $('select#facility_location_name').val([]).change();
        $('select#facility_organization_name').val([]).change();
        $('select#facility_address_city').val([]).change();
        $('select#facility_street_address').val([]).change();
        $('select#facility_zip_code').val([]).change();
        $('select#facility_building_status').val([]).change();        
    });

</script>
@endsection




