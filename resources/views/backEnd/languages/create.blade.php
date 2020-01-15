@extends('backLayout.app')
@section('title')
create language
@stop

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">Create new language</div>

    <div class="panel-body">

        {!! Form::open(['route' => 'languages.store', 'class' => 'form-horizontal']) !!}

        <div class="form-group {{ $errors->has('language_name') ? 'has-error' : ''}}">
            {!! Form::label('language_name', 'language name', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('language_name', null, ['class' => 'form-control']) !!}
                {!! $errors->first('language_name', '<p class="help-block">:message</p>') !!}
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
            <a href="{{route('languages.index')}}" class="btn btn-default">Back</a>
        </div>
    </div>
</div>
{!! Form::close() !!}

@endsection