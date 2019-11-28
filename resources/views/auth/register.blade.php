@extends('frontLayout.app')
@section('title')
Register
@stop
@section('content')

<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<style type="text/css">
.dropdown-menu.open{
  max-height: 300px !important;
}
div.filter-option {
  background: white;
  color: #2c3e50;
}

</style>

<div class = "row">
  <div class="wrapper">
    <div class="col-md-7" style="text-align: center;">
      {!! $layout->register_content !!}
    </div>
    <div class="col-md-5">
      <div class="panel-heading">
         <div class="panel-title text-center">
            <h1 class="title">Register</h1>
          </div>
      </div>
      @if (Session::has('message'))
       <div class="alert alert-{{(Session::get('status')=='error')?'danger':Session::get('status')}} " alert-dismissable fade in id="sessions-hide">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
         <strong>{{Session::get('status')}}!</strong> {!! Session::get('message') !!}
        </div>
      @endif 
       {{ Form::open(array('url' => route('register'), 'class' => 'form-horizontal form-signin','files' => true)) }}    
          {!! csrf_field() !!}
              
              <div class="form-group  {{ $errors->has('first_name') ? 'has-error' : ''}}">
                <label for="first_name" class="cols-sm-2 control-label">First Name</label>
                <div class="cols-sm-10">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                    {!! Form::text('first_name', null, ['class' => 'form-control','placeholder '=>'Enter your firtst name']) !!}
                  </div>
                  {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
                </div>
              </div>
              <div class="form-group  {{ $errors->has('last_name') ? 'has-error' : ''}}">
                <label for="last_name" class="cols-sm-2 control-label">Last Name</label>
                <div class="cols-sm-10">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                    {!! Form::text('last_name', null, ['class' => 'form-control','placeholder '=>'Enter your last name']) !!}
                  </div>
                   {!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group  {{ $errors->has('email') ? 'has-error' : ''}}">
                <label for="email" class="cols-sm-2 control-label">Your Email</label>
                <div class="cols-sm-10">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
                    {!! Form::email('email', null, ['class' => 'form-control','placeholder '=>'E-mail']) !!}
                  </div>
                   {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group  {{ $errors->has('phone_number') ? 'has-error' : ''}}">
                <label for="phone_number" class="cols-sm-2 control-label">Your Cell phone</label>
                <div class="cols-sm-10">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-phone fa" aria-hidden="true"></i></span>
                    {!! Form::number('phone_number', null, ['class' => 'form-control','placeholder '=>'phone_number']) !!}
                  </div>
                   {!! $errors->first('phone_number', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group  {{ $errors->has('organization') ? 'has-error' : ''}}">
                <label for="organization" class="cols-sm-2 control-label">Organization</label>
                <div class="cols-sm-10">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-home fa" aria-hidden="true"></i></span>
                    <select class="form-control selectpicker" data-live-search="true" id="organization" name="organization">
                    @foreach($organization_name_list as $key => $organization)
                        <option value="{{$organization->organization_name}}">{{$organization->organization_name}}</option>
                    @endforeach
                    </select>
                  </div>
                   {!! $errors->first('organization', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group  {{ $errors->has('password') ? 'has-error' : ''}}">
                <label for="password" class="cols-sm-2 control-label">Password</label>
                <div class="cols-sm-10">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                     {!! Form::password('password', ['class' => 'form-control','rel'=>'gp' ,'data-size'=>'10' ,'data-character-set'=>'a-z,A-Z,0-9,#' ,'placeholder '=>'Enter your Password']) !!}
                  
                  </div>
                  {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group  {{ $errors->has('password_confirmation') ? 'has-error' : ''}}">
                <label for="confirm" class="cols-sm-2 control-label">Confirm Password</label>
                <div class="cols-sm-10">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                    {!! Form::password('password_confirmation', ['class' => 'form-control','rel'=>'gp' ,'data-size'=>'10' ,'data-character-set'=>'a-z,A-Z,0-9,#' ,'placeholder '=>'Confirm your Password']) !!}
                  
                  </div>
                  {!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group  {{ $errors->has('password') ? 'has-error' : ''}} ">
                <button class="btn btn-primary btn-lg btn-block register-button" type="submit" >Register</button>
                
              </div>
              <div class="login-register">
                      <a href="{{url('login')}}">Login</a>
                      @if ($errors->has('global'))
                      <span class="help-block danger">
                          <strong style="color:red" >{{ $errors->first('global') }}</strong>
                      </span>
                    @endif 
              </div>     
      {{ Form::close() }}  
    </div> 
  </div>
</div>
@endsection

@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
$(document).ready(function() {
  $('#organization').selectpicker("");
});  
</script>

@endsection