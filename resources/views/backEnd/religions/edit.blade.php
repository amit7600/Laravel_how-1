@extends('backLayout.app')
@section('title')
Edit religion : {{$religion->religion_name}}
@stop

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">Edit religion</div>

    <div class="panel-body">

        {!! Form::model($religion,['route' => array('religions.update',$religion->id), 'class' =>
        'form-horizontal','method' => 'PUT','enctype' => 'multipart/form-data']) !!}

        <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
            {!! Form::label('name', 'Religion name', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
            {!! Form::label('type', 'Religion type', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::select('type',['religion' => 'Religion','faith_tradition' => 'Faith tradition','denominations'
                => 'Denominations','judicatory_body' => 'Judicatory body'], null, ['class' =>
                'form-control','placeholder' => 'Please select type']) !!}
                {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('parent') ? 'has-error' : ''}}">
            {!! Form::label('parent', 'Parent', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('parent', null, ['class' => 'form-control']) !!}
                {!! $errors->first('parent', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('Organizations') ? 'has-error' : ''}}">
            {!! Form::label('organizations', 'Organizations', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('organizations', null, ['class' => 'form-control']) !!}
                {!! $errors->first('Organizations', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('icon') ? 'has-error' : ''}}">
            {!! Form::label('icon', 'icon', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::file('icon', ['class' => 'form-control']) !!}
                {!! $errors->first('icon', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('notes') ? 'has-error' : ''}}">
            {!! Form::label('notes', 'Notes', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::textarea('notes', null, ['class' => 'form-control']) !!}
                {!! $errors->first('notes', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-3">
                {!! Form::submit('Submit', ['class' => 'btn btn-success form-control']) !!}
            </div>
            <a href="{{route('religions.index')}}" class="btn btn-default">Back</a>
        </div>
    </div>
</div>
{!! Form::close() !!}

@endsection