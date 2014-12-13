@extends('layouts.master')

@section('PlaceHolderTitle', 'Edit Application')

@section('PlaceHolderMainForm')

    {{ HTML::breadcrumbs(null, $app->breadcrumbs(), ['Edit']) }}
    <hr />

	{{ Form::model($app, ['method' => 'put', 'action' => ['AppController@update', $app->id]]) }}
        <div class="row">
            <div class="col-md-6">
                {{ Form::control('text', 'name', 'Name', $app->name, $errors) }}
                {{ Form::control('textarea', 'description', 'Description', $app->description, $errors) }}
            </div>
            <div class="col-md-6">
                {{ Form::control('text', 'live_url', 'Live Url', $app->live_url, $errors) }}
                {{ Form::control('text', 'scm_url', 'Source Url', $app->scm_url, $errors) }}
                {{ Form::control('timestamp', 'created_at', 'Created', $app->created_at) }}
                {{ Form::control('timestamp', 'updated_at', 'Updated', $app->updated_at) }}
            </div>
        </div>
        <hr />
        
        <div class="form-group">
            {{ Form::submit('Update', [
                'class' => 'btn btn-primary btn-md'
            ]) }}
            {{ Form::link('Cancel', URL::action('AppController@index'), [
                'class' => 'btn btn-default btn-md'
            ]) }}
            {{ Form::button('Delete', [
                'class' => 'btn btn-default btn-md',
                'data-action' => URL::action('AppController@destroy', $app->id),
                'data-target' => '#confirm-delete',
                'data-title' => $app->name,
                'data-toggle' => 'modal'
            ]) }}
        </div>
	{{ Form::close() }}
    
    {{ HTML::confirm_delete('Delete', Lang::get('api.app_delete_warning')) }}
@stop