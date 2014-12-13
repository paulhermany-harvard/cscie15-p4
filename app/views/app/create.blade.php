@extends('layouts.master')

@section('PlaceHolderTitle', 'Create Application')

@section('PlaceHolderMainForm')

    {{ HTML::breadcrumbs(null, array(['Applications' => URL::action('AppController@index')]), ['Create']) }}
    <hr />

	{{ Form::open(array('action' => 'AppController@store')) }}
        <div class="row">
            <div class="col-md-6">
                {{ Form::control('text', 'name', 'Name', Input::get('name'), $errors) }}
                {{ Form::control('textarea', 'description', 'Description', Input::get('name'), $errors) }}
            </div>
            <div class="col-md-6">
                {{ Form::control('text', 'live_url', 'Live Url', Input::get('live_url'), $errors) }}
                {{ Form::control('text', 'scm_url', 'Source Url', Input::get('scm_url'), $errors) }}
            </div>
        </div>
        <hr />
    
        <div class="form-group">
            {{ Form::submit('Save', array('class' => 'btn btn-primary btn-md')) }}
            <a href="{{ URL::action('AppController@index') }}" class="btn btn-default btn-md">Cancel</a>
        </div>
	{{ Form::close() }}
@stop