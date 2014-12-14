@extends('layouts.master')

@section('PlaceHolderMetaTitle')
<title>Configurely, application configuration management</title>
@stop

@section('PlaceHolderAdditionalPageHead')
{{ HTML::style('css/splash.css') }}
@stop

@section('PlaceHolderMain')

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Configurely</h1>
            <p class="lead">an application catalog and configuration management tool</p>
        </div>
        <div class="col-md-4">
            <div class="jumbotron">
                @if(Auth::user() && !Auth::user()->isGuest())
                <a href="/api/v1/app" class="btn btn-primary btn-lg btn-block">Manage Applications</a>
                @else
                <a href="/signup" class="btn btn-primary btn-lg btn-block">Get Started</a>
                @endif
            </div>
        </div>
    </div>
</div>

<div id="graph"></div>

@stop

@section('PlaceHolderAdditionalScript')
{{ HTML::script('js/lib/d3.js') }}
{{ HTML::script('js/lib/d3.layout.js') }}
{{ HTML::script('js/splash.js') }}
@stop