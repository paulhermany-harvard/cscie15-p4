@extends('layouts.master')

@section('PlaceHolderTitle', 'Setting Index')

@section('PlaceHolderMainForm')

    <h3>
        <a href="{{ URL::action('AppController@index') }}">Apps</a>
        <span> / </span>
        <a href="{{ URL::action('AppController@show', $config->app->id) }}">{{{ $config->app->name }}}</a>
        <span> / </span>
        <a href="{{ URL::action('ConfigController@index', $config->id) }}">{{{ $config->name }}}</a>
        <span> / </span>
        <span>Settings</span>
    </h3>

    <hr />
    
    @foreach($config->settings as $setting)
    <h4>
        <a href="{{ URL::action('SettingController@show', [$config->app->id, $config->id, $setting->id]) }}">{{{ $setting->key }}}</a>
    </h4>
    <hr />
    @endforeach
    
    <a class="btn btn-primary btn-md" href="{{ URL::action('SettingController@create', [$config->app->id, $config->id]) }}">
      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Setting
    </a>
    
@stop