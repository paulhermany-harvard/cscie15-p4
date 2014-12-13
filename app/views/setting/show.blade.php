@extends('layouts.master')

@section('PlaceHolderTitle', 'View Setting')

@section('PlaceHolderMainForm')

    {{ HTML::breadcrumbs(null, $setting->breadcrumbs(), null) }}
    <hr />
    
    <div class="row">
        <div class="col-md-8">
            <h4>
                <a href="{{ URL::action('SettingController@show', [$setting->config->app->id, $setting->config->id, $setting->id]) }}">{{{ $setting->key }}}</a>
            </h4>
            
            <p class="value">{{ HTML::resource( $setting->resourceable ) }}</p>
            
            <p class="updated-at">Last updated {{ $setting->updated_at_display() }}</p>
        </div>
        <div class="col-md-4">
            <a href="{{ URL::action('SettingController@edit', [$setting->config->app->id, $setting->config->id, $setting->id]) }}" class="btn btn-default btn-md">Edit</a>
            <a href="#" data-action="{{ URL::action('SettingController@destroy', [$setting->config->app->id, $setting->config->id, $setting->id]) }}" data-title="{{{ $setting->key }}}" class="btn btn-default btn-md" data-toggle="modal" data-target="#confirm-delete">Delete</a>
        </div>
    </div>
    
    {{ HTML::confirm_delete('Delete', Lang::get('api.config_delete_warning')) }}    
@stop