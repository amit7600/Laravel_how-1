@extends('layouts.app')
@section('title')
Create message
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    #create-message {
        margin-top: 50px;
        width: 40%;
    }

    #create-message .form-group {
        width: 100%;
    }

    button[data-id="contacts"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="groups"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    .form-group button {
        width: 32.96%;
    }

    .form-group a {
        width: 100%%;
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
<div class="wrapper ">
    <!-- Page Content Holder -->
    <div class="col-md-2 left_side_menu">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="{{Request::segment(1) == 'campaigns' ? 'nav-link active' : 'nav-link'}}"
                    href="{{route('campaigns.index')}}">View Campaigns</a>

            </li>
            <li class="nav-item"><a class="{{Request::segment(1) == 'messages' ? 'nav-link active' : 'nav-link'}}"
                    href="{{route('messages.index')}}">View Messages</a></li>
            <li class="nav-item"><a class="nav-link" href="{{route('campaigns.create')}}">Create a Campaign</a></li>
            <li class="nav-item"><a class="{{Request::segment(1) == 'createMessage' ? 'nav-link active' : 'nav-link'}}"
                    href="{{route('createMessage')}}">Create a Message</a></li>
        </ul>
    </div>
    <div class="col-md-10">
        @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissable custom-success-box" style="margin: 15px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong> {{ session()->get('error') }} </strong>
        </div>
        @endif
        @if (session()->has('success'))
        <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong> {{ session()->get('success') }} </strong>
        </div>
        @endif
        {!! Form::open(['route' => 'sendMultipleMessage']) !!}
        <div id="create-message" class="container">
            <h1>Create Message</h1>
            <div class="row">
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4"> </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        {!! Form::select('sending_method',['contact' => 'Contact','group' => 'Group'],null,['class' =>
                        'form-control','id' => 'sending_method' ]) !!}
                    </div>
                </div>
                <div class="form-group" id="contacts" style="display:none;">
                    <label class="control-label sel-label-org pl-4">Select Contacts </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        {!! Form::select('contacts[]',$contactDetail,'',['class' =>
                        $errors->has("contacts") ? "form-control selectpicker has-error" :'form-control
                        selectpicker','multiple' =>
                        'multiple','data-live-search'=>'true','id' => 'contacts']) !!}
                        {!! $errors->first('contacts', '<p class="help-block" style="color: red">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group" id="groups" style="display:none;">
                    <label class="control-label sel-label-org pl-4">Select Groups </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        {!! Form::select('groups[]',$gorupDetail,null,['class' =>
                        $errors->has("groups") ? "form-control selectpicker has-error" :'form-control
                        selectpicker','multiple' => 'multiple','data-live-search'=>'true','id' => 'groups']) !!}
                        {!! $errors->first('groups', '<p class="help-block" style="color: red">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Select Type </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        {!! Form::select('sending_type',['sms' => 'SMS','email' => 'E-mail'],null,['class' =>
                        'form-control','id' => 'sending_type']) !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('subject') ? 'has-error' : ''}}" id="subject"
                    style="display:none">
                    <label class="control-label sel-label-org pl-4">Subject</label>
                    <div class="col-md-12 col-sm-12 col-xs-12 ">
                        {!! Form::text('subject','',['class' =>
                        'form-control']) !!}
                        {!! $errors->first('subject', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Body</label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        {!! Form::textarea('body','',['class' =>
                        'form-control']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-info btn-rounded float-right" id="save-organization-btn"><i
                                class="fa fa-send"></i> Send now</button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
</div>

@endsection
@section('customScript')

<script src="{{asset('js/markerclusterer.js')}}"></script>
<script>
    $(document).ready(function(){
        var sending_method = $('#sending_method').val();
        var sending_type = $('#sending_type').val();
        
        $('#sending_method').on('change',function(){
            sending_method = this.value;
            if(sending_method == 'contact'){
                $('#contacts').show();
                $('#groups').hide();
            }else{
                $('#contacts').hide();
                $('#groups').show();
            }
        });
        $('#sending_type').on('change',function(){
            sending_type = this.value;
           if(sending_type == 'email'){
                $('#subject').show();
            }else{
                $('#subject').hide();
            } 
        });
        if(sending_method == 'contact'){
            $('#contacts').show();
            $('#groups').hide();
        }else{
            $('#contacts').hide();
            $('#groups').show();
        }
        if(sending_type == 'email'){
            $('#subject').show();
        }else{
            $('#subject').hide();
        } 
        
    });
</script>
@endsection