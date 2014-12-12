@extends('layouts.master')

@section('PlaceHolderTitle', 'View Application')

@section('PlaceHolderMainForm')

    <h3>
        <a href="{{ URL::action('AppController@index') }}">Apps</a>
        <span> / </span>
        <span>{{{ $app->name }}}</span>
    </h3>
    <hr />
    
    <div class="row">
        <div class="col-md-8">    
            <p class="description">{{{ $app->description }}}</p>

            @if ($app->live_url || $app->scm_url)
            <dl>
                @if ($app->live_url)
                <dt>Live Url:</dt>
                <dd><a href="{{ urlencode($app['live_url']) }}">{{ $app->live_url }}</a></dd>
                @endif
                @if ($app->scm_url)
                <dt>Source Url:</dt>
                <dd><a href="{{ urlencode($app['scm_url']) }}">{{ $app->scm_url }}</a></dd>
                @endif
            </dl>
            @endif
        </div>
        <div class="col-md-4">
            <a href="{{ URL::action('ConfigController@index', $app->id) }}" class="btn btn-default btn-md">View Configurations</a>
            <a href="{{ URL::action('AppController@edit', $app->id) }}" class="btn btn-default btn-md">Edit</a>
            <a href="#" data-action="{{ URL::action('AppController@destroy', $app->id) }}" data-title="{{{ $app->name }}}" class="btn btn-default btn-md" data-toggle="modal" data-target="#confirm-delete">Delete</a>
        </div>
    </div>
    
    {{ HTML::confirm_delete('Delete', Lang::get('api.app_delete_warning')) }}
@stop