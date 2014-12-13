@extends('layouts.master')

@section('PlaceHolderTitle', 'Setting Index')

@section('PlaceHolderMainForm')

    {{ HTML::breadcrumbs(null, $config->breadcrumbs(), array(['Settings' => URL::action('SettingController@index', [$config->app->id, $config->id])])) }}
    <hr />

    @foreach($config->settings as $setting)
    <div class="row">
        <div class="col-md-8">
            <h4>
                <a href="{{ URL::action('SettingController@show', [$config->app->id, $config->id, $setting->id]) }}">{{{ $setting->key }}}</a>
            </h4>
            
            <p class="value">{{{ $setting->value }}}</p>
            
            <p class="updated-at">Last updated {{ $setting->updated_at_display() }}</p>
        </div>
        <div class="col-md-4">
            <a href="{{ URL::action('SettingController@edit', [$setting->config->app->id, $setting->config->id, $setting->id]) }}" class="btn btn-default btn-md">Edit</a>
            <a href="#" data-action="{{ URL::action('SettingController@destroy', [$setting->config->app->id, $setting->config->id, $setting->id]) }}" data-title="{{{ $setting->key }}}" class="btn btn-default btn-md" data-toggle="modal" data-target="#confirm-delete">Delete</a>
        </div>
    </div>
    <hr />
    @endforeach
    
    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-primary btn-md" href="{{ URL::action('SettingController@create', [$config->app->id, $config->id]) }}">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Setting
            </a>
        </div>
    </div>
    
    {{ HTML::confirm_delete('Delete', Lang::get('api.config_delete_warning')) }}
@stop