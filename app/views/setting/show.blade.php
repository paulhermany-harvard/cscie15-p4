@extends('layouts.master')

@section('PlaceHolderTitle', 'View Setting')

@section('PlaceHolderMainForm')

    <h3>
        <a href="{{ URL::action('AppController@index') }}">Apps</a>
        <span> / </span>
        <a href="{{ URL::action('AppController@show', $setting->config->app->id) }}">{{{ $setting->config->app->name }}}</a>
        <span> / </span>
        <a href="{{ URL::action('ConfigController@index', $setting->config->app->id) }}">Configurations</a>
        <span> / </span>
        <a href="{{ URL::action('ConfigController@show', [$setting->config->app->id, $setting->config->id]) }}">{{{ $setting->config->name }}}</a>
        <span> / </span>
        <a href="{{ URL::action('SettingController@index', [$setting->config->app->id, $setting->config->id]) }}">Settings</a>
        <span> / </span>
        <span>{{{ $setting->key }}}</span>
    </h3>
    
    {{ HTML::resource( $setting->resourceable ) }}
    
    <hr />
    
    {{ Form::open(['method' => 'DELETE', 'action' => ['SettingController@destroy', $setting->config->app->id, $setting->config->id, $setting->id]]) }}
        <a href="{{ URL::action('SettingController@edit', [$setting->config->app->id, $setting->config->id, $setting->id]) }}" class="btn btn-primary btn-md">Edit</a>
        {{ Form::submit('Delete', [
            'class' => 'btn btn-link btn-md'
        ]) }}
    {{ Form::close() }}
    
@stop