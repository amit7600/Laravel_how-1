@extends('backLayout.app')
@section('title')
create religion
@stop

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">Create new religion</div>

    <div class="panel-body">

        {!! Form::open(['route' => 'religions.store', 'class' => 'form-horizontal']) !!}

        <div class="form-group {{ $errors->has('religion_name') ? 'has-error' : ''}}">
            {!! Form::label('religion_name', 'Religion name', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('religion_name', null, ['class' => 'form-control']) !!}
                {!! $errors->first('religion_name', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('note') ? 'has-error' : ''}}">
            {!! Form::label('note', 'Note', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::textarea('note', null, ['class' => 'form-control']) !!}
                {!! $errors->first('note', '<p class="help-block">:message</p>') !!}
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