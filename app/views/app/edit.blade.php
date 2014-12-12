@extends('layouts.master')

@section('PlaceHolderTitle', 'Edit Application')

@section('PlaceHolderMainForm')
    <h3>
        <a href="{{ URL::action('AppController@index') }}">Apps</a>
        <span> / </span>
        <a href="{{ URL::action('AppController@show', $app->id) }}">{{{ $app->name }}}</a>
        <span> / </span>
        <span>Edit</span>
    </h3>

	{{ Form::model($app, ['method' => 'put', 'action' => ['AppController@update', $app->id]]) }}
    
        {{ Form::control('text', 'name', 'Name', $app->name, $errors) }}
    
        <div class="form-group name">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', $app->name, array('class' => 'form-control')) }}
            <p class="text-danger">{{ $errors->first('name') }}</p>
        </div>
        <div class="form-group description">
            {{ Form::label('description', 'Description') }}
            {{ Form::textarea('description', $app->description, array('class' => 'form-control')) }}
            <p class="text-danger">{{ $errors->first('description') }}</p>
        </div>
        <div class="form-group live_url">
            {{ Form::label('live_url', 'Live Url') }}
            {{ Form::text('live_url', $app->live_url, array('class' => 'form-control')) }}
            <p class="text-danger">{{ $errors->first('live_url') }}</p>
        </div>
        <div class="form-group scm_url">
            {{ Form::label('scm_url', 'Source Url') }}
            {{ Form::text('scm_url', $app->scm_url, array('class' => 'form-control')) }}
            <p class="text-danger">{{ $errors->first('scm_url') }}</p>
        </div>
        <div class="form-group created_at">
            {{ Form::label('created_at', 'Created') }}
            {{ Form::timestamp('created_at', $app->created_at, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::submit('Update', [
                'class' => 'btn btn-primary btn-md'
            ]) }}
            <a href="#" data-action="{{ URL::action('AppController@destroy', $app->id) }}" data-title="{{{ $app->name }}}" class="btn btn-default btn-md" data-toggle="modal" data-target="#confirm-delete">Delete</a>
            <a href="{{ URL::action('AppController@index') }}" class="btn btn-default btn-md">Cancel</a>
        </div>
	{{ Form::close() }}
    
    {{ HTML::confirm_delete('Delete', Lang::get('api.app_delete_warning')) }}
@stop