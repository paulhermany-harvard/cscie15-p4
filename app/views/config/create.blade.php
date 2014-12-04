@extends('layouts.master')

@section('PlaceHolderTitle', 'Create Configuration')

@section('PlaceHolderMainForm')

    <h3>
        <a href="{{ URL::action('AppController@index') }}">Apps</a>
        <span> / </span>
        <a href="{{ URL::action('AppController@show', $app->id) }}">{{{ $app->name }}}</a>
        <span> / </span>
        <a href="{{ URL::action('ConfigController@index', $app->id) }}">Configurations</a>
        <span> / </span>
        <span>Create</span>
    </h3>

	{{ Form::open(array('action' => array('ConfigController@store', $app->id) )) }}
        <div class="form-group name">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', null, array('class' => 'form-control')) }}
        </div>        
        <div class="form-group description">
            {{ Form::label('description', 'Description') }}
            {{ Form::textarea('description', null, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::submit('Save', array('class' => 'btn btn-primary btn-md')) }}
            <a href="{{ URL::action('ConfigController@index', $app->id) }}" class="btn btn-default btn-md">Cancel</a>
        </div>
	{{ Form::close() }}
@stop