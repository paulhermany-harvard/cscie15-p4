@extends('layouts.master')

@section('PlaceHolderTitle', 'View Application')

@section('PlaceHolderMainForm')

    <h3>
        <a href="{{ URL::action('AppController@index') }}">Apps</a>
        <span> / </span>
        <span>{{{ $app->name }}}</span>
    </h3>
    
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
    
    <a href="{{ URL::action('ConfigController@index', $app->id) }}" class="btn btn-default btn-md">Configurations</a>  
    
    <hr />
    
    {{ Form::open(['method' => 'DELETE', 'action' => ['AppController@destroy', $app->id]]) }}
        <a href="{{ URL::action('AppController@edit', $app->id) }}" class="btn btn-primary btn-md">Edit</a>
        {{ Form::submit('Delete', [
            'class' => 'btn btn-link btn-md'
        ]) }}
    {{ Form::close() }}
    
@stop