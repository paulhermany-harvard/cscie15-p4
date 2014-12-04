@extends('layouts.master')

@section('PlaceHolderTitle', 'Edit Configuration')

@section('PlaceHolderMainForm')

    <h3>
        <a href="{{ URL::action('AppController@index') }}">Apps</a>
        <span> / </span>
        <a href="{{ URL::action('AppController@show', $config->app->id) }}">{{{ $config->app->name }}}</a>
        <span> / </span>
        <a href="{{ URL::action('ConfigController@index', $config->app->id) }}">Configurations</a>
        <span> / </span>
        <a href="{{ URL::action('ConfigController@show', [$config->app->id, $config->id]) }}">{{{ $config->name }}}</a>
        <span> / </span>
        <span>Edit</span>
    </h3>

	{{ Form::model($config, ['method' => 'put', 'action' => ['ConfigController@update', $config->app->id, $config->id]]) }}
        <div class="form-group name">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', $config->name, array('class' => 'form-control')) }}
        </div>
        <div class="form-group description">
            {{ Form::label('description', 'Description') }}
            {{ Form::textarea('description', $config->description, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::submit('Update', [
                'class' => 'btn btn-primary btn-md'
            ]) }}
            <a href="{{ URL::action('ConfigController@show', [$config->app->id, $config->id]) }}" class="btn btn-default btn-md">Cancel</a>
        </div>
	{{ Form::close() }}
@stop