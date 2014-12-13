@extends('layouts.master')

@section('PlaceHolderTitle', 'Edit Configuration')

@section('PlaceHolderMainForm')

    {{ HTML::breadcrumbs(null, $config->breadcrumbs(), ['Edit']) }}
    <hr />

	{{ Form::model($config, ['method' => 'put', 'action' => ['ConfigController@update', $config->app->id, $config->id]]) }}
        <div class="row">
            <div class="col-md-6">
                {{ Form::control('text', 'name', 'Name', $config->name, $errors) }}
                {{ Form::control('textarea', 'description', 'Description', $config->description, $errors) }}
            </div>
            <div class="col-md-6">
                {{ Form::control('timestamp', 'created_at', 'Created', $config->created_at) }}
                {{ Form::control('timestamp', 'updated_at', 'Updated', $config->updated_at) }}
            </div>
        </div>
        <hr />
        
        <div class="form-group">
            {{ Form::submit('Update', [
                'class' => 'btn btn-primary btn-md'
            ]) }}
            {{ Form::link('Cancel', URL::action('ConfigController@index', $config->app->id), [
                'class' => 'btn btn-default btn-md'
            ]) }}
            {{ Form::button('Delete', [
                'class' => 'btn btn-default btn-md',
                'data-action' => URL::action('ConfigController@destroy', [$config->app->id, $config->id]),
                'data-target' => '#confirm-delete',
                'data-title' => $config->name,
                'data-toggle' => 'modal'
            ]) }}
        </div>
	{{ Form::close() }}
    
    {{ HTML::confirm_delete('Delete', Lang::get('api.app_delete_warning')) }}
@stop