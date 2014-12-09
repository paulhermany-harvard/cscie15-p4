@extends('layouts.master')

@section('PlaceHolderMetaTitle')
<title>Configurely, wicked simple configuration management</title>
@stop

@section('PlaceHolderAdditionalPageHead')
{{ HTML::style('css/splash.css') }}
@stop

@section('PlaceHolderMain')
<div id="graph"></div>
<div class="container">
 <!--
        <h1>Configurely</h1>
        <p class="lead">hello world</p>
 -->
</div>
@stop

@section('PlaceHolderAdditionalScript')
{{ HTML::script('js/lib/d3.js') }}
{{ HTML::script('js/lib/d3.layout.js') }}
{{ HTML::script('js/splash.js') }}
@stop