@extends('layouts.master')

@section('PlaceHolderTitle', 'Create Configuration')

@section('PlaceHolderMainForm')

    {{ HTML::breadcrumbs($app->breadcrumbs(), array(['Configurations' => URL::action('ConfigController@index', $app->id)]), ['Create']) }}
    <hr />

	{{ Form::open(array('action' => array('ConfigController@store', $app->id) )) }}
        <div class="row">
            <div class="col-md-6">
                {{ Form::control('text', 'name', 'Name', Input::get('name'), $errors) }}
                {{ Form::control('textarea', 'description', 'Description', Input::get('name'), $errors) }}
            </div>
        </div>
        <hr />
  
        <div class="form-group">
            {{ Form::submit('Save', array('class' => 'btn btn-primary btn-md')) }}
            <a href="{{ URL::action('ConfigController@index', $app->id) }}" class="btn btn-default btn-md">Cancel</a>
        </div>
	{{ Form::close() }}
@stop