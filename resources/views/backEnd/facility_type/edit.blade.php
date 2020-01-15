@extends('backLayout.app')
@section('title')
Edit Facility type : {{$FacilityType->facility_type}}
@stop

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">Edit Facility Type</div>

    <div class="panel-body">

        {!! Form::model($FacilityType,['route' => array('FacilityTypes.update',$FacilityType->id), 'class' =>
        'form-horizontal','method' => 'PUT']) !!}

        <div class="form-group {{ $errors->has('facility_type') ? 'has-error' : ''}}">
            {!! Form::label('facility_type', 'Facility Type', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('facility_type', null, ['class' => 'form-control']) !!}
                {!! $errors->first('facility_type', '<p class="help-block">:message</p>') !!}
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
            <a href="{{route('FacilityTypes.index')}}" class="btn btn-default">Back</a>
        </div>
    </div>
</div>
{!! Form::close() !!}

@endsection