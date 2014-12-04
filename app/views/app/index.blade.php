@extends('layouts.master')

@section('PlaceHolderTitle', 'Application Index')

@section('PlaceHolderMainForm')

    <h3>
        <span>Apps</span>
    </h3>

    <hr />
    @foreach($apps as $app)
    
    <h4>
        <a href="{{ URL::action('AppController@show', $app->id) }}">{{{ $app->name }}}</a>
    </h4>
    
    <p class="description">{{{ $app->description }}}</p>
    
    @if ($app['live_url'] || $app['scm_url'])
        <dl>
        @if ($app['live_url'])
            <dt>Live Url:</dt>
            <dd><a href="{{ urlencode($app['live_url']) }}">{{{ $app['live_url'] }}}</a></dd>
        @endif
        @if ($app['scm_url'])
            <dt>Source Url:</dt>
            <dd><a href="{{ urlencode($app['scm_url']) }}">{{{ $app['scm_url'] }}}</a></dd>
        @endif
        </dl>
    @endif
    
    <p class="updated-at">Last updated {{ $app->updated_at_display() }}</p>
    
    <hr />
    @endforeach
    
    <a class="btn btn-primary btn-md" href="{{ URL::action('AppController@create') }}">
      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add App
    </a>
@stop