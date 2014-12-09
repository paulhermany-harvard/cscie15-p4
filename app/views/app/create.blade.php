@extends('layouts.master')

@section('PlaceHolderTitle', 'Create Application')

@section('PlaceHolderMainForm')
    <h3>
        <a href="{{ URL::action('AppController@index') }}">Apps</a>
        <span> / </span>
        <span>Create</span>
    </h3>

	{{ Form::open(array('action' => 'AppController@store')) }}
        <div class="form-group name">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', null, array('class' => 'form-control')) }}
            <p class="text-danger">{{ $errors->first('name') }}</p>
        </div>
        <div class="form-group description">
            {{ Form::label('description', 'Description') }}
            {{ Form::textarea('description', null, array('class' => 'form-control')) }}
            <p class="text-danger">{{ $errors->first('description') }}</p>
        </div>
        <div class="form-group live_url">
            {{ Form::label('live_url', 'Live Url') }}
            {{ Form::text('live_url', null, array('class' => 'form-control')) }}
            <p class="text-danger">{{ $errors->first('live_url') }}</p>
        </div>
        <div class="form-group scm_url">
            {{ Form::label('scm_url', 'Source Url') }}
            {{ Form::text('scm_url', null, array('class' => 'form-control')) }}
            <p class="text-danger">{{ $errors->first('scm_url') }}</p>
        </div>
        <div class="form-group">
            {{ Form::submit('Save', array('class' => 'btn btn-primary btn-md')) }}
            <a href="{{ URL::action('AppController@index') }}" class="btn btn-default btn-md">Cancel</a>
        </div>
	{{ Form::close() }}
@stop