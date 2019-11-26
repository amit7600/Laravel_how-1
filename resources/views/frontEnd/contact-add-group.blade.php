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
        <h1>Add Contact to Group</h1>
        <form action="/contact/{{$contact->contact_recordid}}/update_group" method="GET">
            <div class="row">                
                <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Static Goup list: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="contact_group_name" name="contact_group_name">
                            @foreach($groups as $key => $group)                                
                                <option value="{{$group->group_name}}" @if ($contact->contact_group == $group->group_recordid) selected @endif>{{$group->group_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group"> 
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-rounded" id="save-contact-btn"><i class="fa fa-save"></i>Save</button>
                        <a href="/contact/{{$contact->contact_recordid}}" class="btn btn-success btn-rounded" id="view-contact-btn"><i class="fa fa-eye"></i>Close</a>
                    </div>                   
                </div>
            </div>
        </form>
    </div>
</div>

<script>  
    
</script>
@endsection




