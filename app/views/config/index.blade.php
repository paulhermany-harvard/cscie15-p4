@extends('layouts.master')

@section('PlaceHolderTitle', 'Configuration Index')

@section('PlaceHolderMainForm')

    {{ HTML::breadcrumbs(null, $app->breadcrumbs(), array(['Configurations' => URL::action('ConfigController@index', $app->id)])) }}
    <hr />
    
    @foreach($app->configs as $config)
    <div class="row">
        <div class="col-md-8">
            <h4>
                <a href="{{ URL::action('ConfigController@show', [$config->app->id, $config->id]) }}">{{{ $config->name }}}</a>
            </h4>
            
            <p class="description">{{ $config->description }}</p>
            
            <p class="updated-at">Last updated {{ $config->updated_at_display() }}</p>
        </div>
        <div class="col-md-4">
            <a href="{{ URL::action('SettingController@index', [$config->app->id, $config->id]) }}" class="btn btn-default btn-md">View Settings</a>
            <a href="{{ URL::action('ConfigController@edit', [$config->app->id, $config->id]) }}" class="btn btn-default btn-md">Edit</a>
            <a href="#" data-action="{{ URL::action('ConfigController@destroy', [$config->app->id, $config->id]) }}" data-title="{{{ $config->name }}}" class="btn btn-default btn-md" data-toggle="modal" data-target="#confirm-delete">Delete</a>
        </div>
    </div>
    <hr />
    @endforeach
    
    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-primary btn-md" href="{{ URL::action('ConfigController@create', $app->id) }}">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Configuration
            </a>
        </div>
    </div>
    
    {{ HTML::confirm_delete('Delete', Lang::get('api.config_delete_warning')) }}
@stop