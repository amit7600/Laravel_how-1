@extends('layouts.app')
@section('title')
Organization Edit
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
    .organization-details-div.org .dropdown.bootstrap-select.form-control {
        padding: 0 15px;
    }
    .delete-btn-div {
        text-align: center;
    }
    #view-organization-btn {
        float: right;
    }
    h1 {
        text-align: center;
    }

</style>

@section('content')
<div class="wrapper">
    <div id="organizations-edit-content" class="container">
        <h1>Edit Organization</h1>
        <div class="form-group delete-btn-div">
            <button class="btn btn-danger delete-td" id="delete-organization-btn" value="{{$organization->organization_recordid}}" data-toggle="modal" data-target=".bs-delete-modal-lg"><i class="fa fa-fw fa-remove"></i>Delete</button>
        </div>
        <form action="/organization/{{$organization->organization_recordid}}/update" method="GET">
            <div class="row"> 
                
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Organization Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                        <input class="form-control selectpicker"  type="text" id="organization_organization_name" name="organization_organization_name" value="{{$organization->organization_name}}">
                    </div>
                </div>
                <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Organization Type: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="organization_organization_type" name="organization_organization_type">
                            @foreach($organization_type_list as $key => $org_type)                                
                                <option value="{{$org_type}}" @if ($organization->organization_type == $org_type) selected @endif>{{$org_type}}</option>
                            @endforeach
                        </select>
                    </div>           
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Organization ID: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                        <input class="form-control selectpicker"  type="text" id="organization_organization_id" name="organization_organization_id" value="{{$organization->organization_alt_id}}">
                    </div>
                </div> 
                <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Religion: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="organization_organization_religion" name="organization_organization_religion">
                            @foreach($organization_religion_list as $key => $org_religion)                                
                                <option value="{{$org_religion}}" @if ($organization->organization_religion == $org_religion) selected @endif>{{$org_religion}}</option>
                            @endforeach
                        </select>
                    </div>           
                </div>
                <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Faith Tradition: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="organization_organization_faith_tradition" name="organization_organization_faith_tradition">
                            @foreach($organization_faith_tradition_list as $key => $org_faith_tradition)                                
                                <option value="{{$org_faith_tradition}}" @if ($organization->organization_faith_tradition == $org_faith_tradition) selected @endif>{{$org_faith_tradition}}</option>
                            @endforeach
                        </select>
                    </div>           
                </div>  
                <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Denomination: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="organization_organization_denomination" name="organization_organization_denomination">
                            @foreach($organization_denomination_list as $key => $org_denomination)                                
                                <option value="{{$org_denomination}}" @if ($organization->organization_denomination == $org_denomination) selected @endif>{{$org_denomination}}</option>
                            @endforeach
                        </select>
                    </div>           
                </div>
                <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Judicatory Body: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="organization_organization_judicatory_body" name="organization_organization_judicatory_body">
                            @foreach($organization_judicatory_body_list as $key => $org_judicatory_body)                                
                                <option value="{{$org_judicatory_body}}" @if ($organization->organization_judicatory_body == $org_judicatory_body) selected @endif>{{$org_judicatory_body}}</option>
                            @endforeach
                        </select>
                    </div>           
                </div>            
                
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Facility Address: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                        <input class="form-control selectpicker"  type="text" id="organization_organization_facility_address" name="organization_organization_facility_address" value="{{$organization_location_address}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Website: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                        <input class="form-control selectpicker"  type="text" id="organization_organization_website" name="organization_organization_website" value="{{$organization->organization_url}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Facebook: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                        <input class="form-control selectpicker"  type="text" id="organization_organization_facebook" name="organization_organization_facebook" value="{{$organization->organization_facebook}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4"># C. Board: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                        <input class="form-control selectpicker"  type="text" id="organization_organization_c_board" name="organization_organization_c_board" value="{{$organization->organization_c_board}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Internete Access: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">                     
                        @if ($organization->organization_internet_access == 'yes')
                        <input type="checkbox" id="organization_organization_internete_access" name="organization_organization_internete_access" checked>
                        @endif
                        @if ($organization->organization_internet_access == 'no')
                        <input type="checkbox" id="organization_organization_internete_access" name="organization_organization_internete_access">
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Comments: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                        <input class="form-control selectpicker" type="text" rows="10" cols="30" id="organization_organization_comments" name="organization_organization_comments" value="{{$organization->organization_description}}">
                    </div>
                </div>
                <div class="form-group"> 
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-rounded" id="save-organization-btn"><i class="fa fa-save"></i>Save</button>
                        <a href="/organization/{{$organization->organization_recordid}}" class="btn btn-success btn-rounded" id="view-organization-btn"><i class="fa fa-eye"></i>Close</a>
                    </div>                   
                </div>
            </div>
        </form>
        <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="/organization_delete_filter" method="POST" id="organization_delete_filter">
                        {!! Form::token() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Delete Organization</h4>
                        </div>
                        <div class="modal-body">
                            
                            <input type="hidden" id="organization_recordid" name="organization_recordid">
                            <h4>Are you sure to delete this organization?</h4>
                            
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
        $('input#organization_recordid').val(value);
    });
</script>
@endsection




