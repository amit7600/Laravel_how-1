@extends('layouts.app')
@section('title')
Create Campaigns
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

    .form-group button,
    .form-group a.btn {
        width: 32.8%;
    }

    /*.form-group a {
        width: 32.8%;
    }*/

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

    .button_dropdown .bootstrap-select>.dropdown-toggle {
        height: 34px;
    }
</style>

@section('content')

<div class="wrapper">
    <div class="col-md-2 left_side_menu">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="{{Request::segment(1) == 'campaigns' && Request::segment(2)=='' ? 'nav-link active' : 'nav-link'}}"
                    href="{{route('campaigns.index')}}">View Campaigns</a>

            </li>
            <li class="nav-item"><a class="{{Request::segment(1) == 'messages' ? 'nav-link active' : 'nav-link'}}"
                    href="{{route('messages.index')}}">View Messages</a></li>
            <li class="nav-item"><a
                    class="{{Request::segment(1) == 'campaigns' && Request::segment(2)=='create' ? 'nav-link active' : 'nav-link'}}"
                    href="{{route('campaigns.create')}}">Create a Campaign</a></li>
            <li class="nav-item"><a class="{{Request::segment(1) == 'createMessage' ? 'nav-link active' : 'nav-link'}}"
                    href="{{route('createMessage')}}">Create a Message</a></li>
        </ul>
    </div>
    <!-- Page Content Holder -->
    <div id="organizations-edit-content" class="container">

        <h1>Create New Campaign</h1>
        @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
        @endif

        @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
        @endif
        <!-- <form action="/" method="GET"> -->

        {!! Form::open(['route' => 'campaigns.store','class' => 'form-horizontal
        form-label-left','enctype' => 'multipart/form-data']) !!}
        <div class="row">
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><strong>Name</strong></label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <!-- <input class="form-control selectpicker"  type="text" id="name" name="name" value=""> -->
                    {!! Form::text('name',null,['class'=> 'form-control selectpicker','id'=>'name'])!!}
                    @if ($errors->has('name'))
                    <div class="error" style="color:red;">
                        {{ $errors->first('name') }}
                    </div>
                    @endif
                </div>

            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><strong>Type</strong></label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <!-- <input type="radio" class="form-check-input" value="1" name="campaign_type" checked="checked" >Email -->
                            {!! Form::radio('campaign_type',1,true,['class'=>
                            'form-check-input','id'=>'campaign_type','onclick' => 'selectType(this)'])!!} Email
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <!-- <input type="radio" class="form-check-input" value="2" name="campaign_type">SMS -->
                            {!! Form::radio('campaign_type',2,null,['class'=>
                            'form-check-input','id'=>'campaign_type','onclick' => 'selectType(this)'])!!} SMS
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <!-- <input type="radio" class="form-check-input" value="3" name="campaign_type">Audio Recording -->
                            {!! Form::radio('campaign_type',3,null,['class'=>
                            'form-check-input','id'=>'campaign_type','onclick' => 'selectType(this)'])!!} Audio
                            Recording

                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <!-- <input type="radio" class="form-check-input" value="3" name="campaign_type">Audio Recording -->
                            {!! Form::radio('campaign_type',4,null,['class'=>
                            'form-check-input','id'=>'campaign_type','onclick' => 'selectType(this)'])!!} SMS and Audio
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group" id="subject_content">
                <label class="control-label sel-label-org pl-4"><strong>Subject</strong></label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <!-- <input class="form-control selectpicker"  type="text" id="subject" name="subject" value="" placeholder="For email communications only"> -->
                    {!! Form::text('subject',null,['class'=> 'form-control selectpicker','id'=>'subject'])!!}
                </div>
            </div>
            <div class="form-group" id="body_content">
                <label class="control-label sel-label-org pl-4"><strong>Body</strong></label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <!-- <textarea class="form-control selectpicker" rows="4" name="body" id="body"></textarea> -->
                    {!! Form::textarea('body',null,['class'=> 'form-control selectpicker','id'=>'body'])!!}
                </div>
            </div>
            <div class="form-group" id="file_content">
                <div id="attechment_content">
                    <label class="control-label sel-label-org pl-4"><strong>Attechment</strong></label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                        <input type="file" id="campaign_file" name="attechment_campaign_file">
                    </div>
                    <!-- @if ($errors->has('attechment_campaign_file'))
                    <div class="error" style="color:red;">
                        {{ $errors->first('attechment_campaign_file') }}
                    </div>
                    @endif -->
                </div>
                <div id="audio_content">
                    <label class="control-label sel-label-org pl-4"><strong>Audio Recording</strong></label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                        <input type="file" id="campaign_file" name="audio_campaign_file">
                    </div>
                    @if ($errors->has('audio_campaign_file'))
                    <div class="error" style="color:red;">
                        {{ $errors->first('audio_campaign_file') }}
                    </div>
                    @endif
                </div>

                <!-- @if(Session::has('imagePath')) 
                    <img src="/{{ Session::get('imagePath')}}" id="imagePath">  
                    @endif  -->
            </div>

            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><strong>Groups</strong></label>
                <!-- <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                        <button class="btn btn-primary" value="Add">+ Add</button>
                    </div> -->
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="religion-div" class="button_dropdown">
                        {!! Form::select('group_id[]',$groupList,null,['class'
                        =>'form-control selectpicker',
                        'multiple'=>'multiple','data-live-search'=>'true', 'id'=>'group_id'] ) !!}
                    </div>
                    @if ($errors->has('group_id'))
                    <div class="error" style="color:red;">
                        {{ $errors->first('group_id') }}
                    </div>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><strong>Sending type</strong></label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            {!! Form::radio('sending_type',1,true,['class'=>
                            'form-check-input','id'=>'sending_type','onclick' => 'sendingType(this)'])!!} Immediately
                        </label>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            {!! Form::radio('sending_type',2,false,['class'=>
                            'form-check-input','id'=>'sending_type','onclick' => 'sendingType(this)'])!!} Schedule
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group" id="schedule">
                <label class="control-label sel-label-org pl-4"><strong>Schedule</strong></label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    {!! Form::text('schedule_date',null,['class'=> 'form-control
                    selectpicker date form_datetime','id'=>'scheduleDate','readonly' => 'readonly'])!!}
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <a href="{{route('campaigns.index')}}" class="btn btn-danger btn-rounded" name="close"><i
                            class="fa fa-times" style="color:#ffffff"></i> Close</a>
                    <button type="submit" class="btn btn-warning btn-rounded" style="color:#000;" name="save_continue"
                        value="save"><i class="fa fa-check"></i> Save</button>
                    <button type="submit" class="btn btn-success btn-rounded" name="save_continue" value="continue"><i
                            class="fa fa-save"></i> Continue </button>
                </div>
            </div>
        </div>

        {!! Form::close() !!}
        <!-- </form> -->
    </div>
</div>

@endsection
@section('customScript')
<script src="{{asset('js/markerclusterer.js')}}"></script>
<script type="text/javascript">
    // $('#scheduleDate').datetimepicker({ 
    //   format: "MM/DD/YYYY hh:00", 
    //   value:'2015/04/15 05:06'
    // });
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
    var value = $('input[name=campaign_type]:checked').val();
    val = {
        'value':value
    }
    selectType(val)
    function selectType(val)
    {
        if(val.value == 1){
            $('#subject_content').show()
            $('#attechment_content').show();
            $('#body_content').show()
            $('#audio_content').hide()
            $('#file_content').show()
            
        }else if(val.value == 2){
            $('#subject_content').hide()
            $('#attechment_content').hide();
            $('#body_content').show()
            $('#file_content').hide()
           
        }else if(val.value == 3){

            $('#subject_content').hide()
            $('#attechment_content').hide();
            $('#body_content').hide()
            $('#audio_content').show()
            $('#file_content').show()
           
        }else{
            $('#audio_content').show();
            $('#body_content').show();
            $('#subject_content').hide();
            $('#attechment_content').hide();
            $('#file_content').show();
        }    
    }
    var sending_val = $('input[name=sending_type]:checked').val();
    sending_val = {
    'value':sending_val
    }
    sendingType(sending_val)

    function sendingType(val){
        if(val.value == 2){
            $('#schedule').show();
        }else{
            $('#schedule').hide();
        }
    }

</script>

@endsection