@extends('layouts.app')
@section('title')
Contact Edit
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">   
    
    #contacts-edit-content {
        margin-top: 50px;
        width: 35%;
    }
    
    #contacts-edit-content .form-group {
        width: 100%;
    }

    button[data-id="contact_group_name"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    
    .form-group button {
        width: 49.5%;
    }

    .form-group a {
        width: 49.5%;
    }

    @media only screen and (max-width: 768px) {
        .form-group button {
            width: 100%;
        }
        .form-group a {
            width: 49.5%;
        }
    }
    .contact-details-div.org .dropdown.bootstrap-select.form-control {
        padding: 0 15px;
    }
</style>

@section('content')
<div class="wrapper">
    <div id="contacts-edit-content" class="container">
        <h1>Add Contacts to Dynamic Group</h1>
        {{-- <form action="/contacts/contacts_update_dynamic_group" method="GET"> --}}
            {!! Form::open(['route' => 'contacts_update_dynamic_group']) !!}
            <div class="row">                
                <!-- <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Dynamic Goup list: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="contact_group_name" name="contact_group_name">
                            @foreach($groups as $key => $group)                                
                                <option value="{{$group->group_name}}">{{$group->group_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div> -->
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Group Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 group-details-div">
                        <input class="form-control selectpicker"  type="text" id="group_name" name="group_name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Group Email: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 group-details-div">
                        <input class="form-control selectpicker"  type="text" id="group_email" name="group_email">    
                    </div>
                </div>
                <div class="form-group" style="text-align: center;">
                    <h3 class="control-label sel-label-org pl-4">Filter Criteria of this Group</h3>
                </div>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12 group-details-div">
                        @if (json_decode($filters_json)->religion_filter != null)
                            <p><b>Religion:</b>   {{json_decode($filters_json)->religion_filter}}</p>
                        @endif 
                        @if (json_decode($filters_json)->faith_tradition_filter != null)
                            <p><b>Faith Tradition:</b>  {{json_decode($filters_json)->faith_tradition_filter}}</p>
                        @endif 
                        @if (json_decode($filters_json)->denomination_filter != null)
                            <p><b>Denomination:</b>  {{json_decode($filters_json)->denomination_filter}}</p>
                        @endif 
                        @if (json_decode($filters_json)->judicatory_body_filter != null)
                            <p><b>Judicatory Body:</b>  {{json_decode($filters_json)->judicatory_body_filter}}</p>
                        @endif 
                        @if (json_decode($filters_json)->contact_address_filter != null)
                            <p><b>Contact Address:</b>  {{json_decode($filters_json)->contact_address_filter}}</p>
                        @endif 
                        @if (json_decode($filters_json)->contact_zipcode_filter != null)
                            <p><b>Zip code:</b>  {{json_decode($filters_json)->contact_zipcode_filter}}</p>
                        @endif 
                        @if (json_decode($filters_json)->contact_borough_filter != null)
                            <p><b>Borough:</b>  {{json_decode($filters_json)->contact_borough_filter}}</p>
                        @endif 
                        @if (json_decode($filters_json)->email_filter != 'All')
                            <p><b>Email:</b>  {{json_decode($filters_json)->email_filter}}</p>
                        @endif 
                        @if (json_decode($filters_json)->phone_filter != 'All')
                            <p><b>Phone:</b>  {{json_decode($filters_json)->phone_filter}}</p>
                        @endif 
                    </div>
                </div>
                <input type="hidden" name="checked_contact_terms" value="{{$checked_terms}}">
                <input type="hidden" name="filters_criteria" value="{{$filters_json}}">
                <div class="form-group"> 
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-rounded" id="save-contact-btn"><i class="fa fa-save"></i>Save</button>
                        <a href="/contacts" class="btn btn-success btn-rounded" id="view-contact-btn"><i class="fa fa-eye"></i>Close</a>
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




