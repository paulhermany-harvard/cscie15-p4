@extends('layouts.master')

@section('PlaceHolderTitle', 'View Configuration')

@section('PlaceHolderMainForm')

    {{ HTML::breadcrumbs(null, $config->breadcrumbs(), null) }}
    <hr />
    
    <div class="row">
        <div class="col-md-8">
            <h4>
                <a href="{{ URL::action('ConfigController@show', [$config->app->id, $config->id]) }}">{{{ $config->name }}}</a>
            </h4>
            
            <p class="description">{{{ $config->description }}}</p>
            
            <p class="updated-at">Last updated {{ $config->updated_at_display() }}</p>
        </div>
        <div class="col-md-4">
            <a href="{{ URL::action('SettingController@index', [$config->app->id, $config->id]) }}" class="btn btn-default btn-md">View Settings</a>
            <a href="{{ URL::action('ConfigController@edit', [$config->app->id, $config->id]) }}" class="btn btn-default btn-md">Edit</a>
            <a href="#" data-action="{{ URL::action('ConfigController@destroy', [$config->app->id, $config->id]) }}" data-title="{{{ $config->name }}}" class="btn btn-default btn-md" data-toggle="modal" data-target="#confirm-delete">Delete</a>
        </div>
    </div>
    
    {{ HTML::confirm_delete('Delete', Lang::get('api.config_delete_warning')) }}
@stop