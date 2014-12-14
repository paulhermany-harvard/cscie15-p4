@extends('layouts.master')

@section('PlaceHolderTitle', 'About')

@section('PlaceHolderMain')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>What is this?</h1>
            <p class="lead">Configurely.com is a web-based application catalog and configuration management tool.</p>
            <dl>
                <dt>.. an application catalog</dt>
                <dd>is a list of software packages (e.g. web applications). Frequently used in conjunction with a service management system, an application catalog serves as a both a software manifest for IT support staff as well as a index of technical resources for end users.</dd>
                <dt>.. configuration management tool</dt>
                <dd>is a software system for managing application settings. A centralized system for securely storing and maintaining application settings can alleviate common problems with change management.</dd>
            </dl>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2>How does it work?</h2>
        </div>
        <div class="col-md-4">
            <div class="well well-lg">
                <h3>Step 1: Add Apps</h3>
                <p>An application is any type of configurable software package. Add web or desktop applications, vended products, mobile apps, cron jobs, script packages, etc.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="well well-lg">
                <h3>Step 2: Add Configs</h3>
                <p>A configuration is simply a named container for your application's settings. Typically this will correspond with the application environment such as development, stage, production, etc.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="well well-lg">
                <h3>Step 3: Add Settings</h3>
                <p>A setting is simply a key/value pair, but you can store pretty much anything including strings, integers, boolean values, and files.</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2>What is on the homepage?</h2>
            <p>If you haven't yet created an account, then the homepage shows a visual representation of a randomly generated set of application configurations.</p>
            <p>Once you've logged in, the homepage will show all of your applications in a hierarchical, interactive partition map powered by <a href="http://d3js.org/">d3js</a>.</p>
        </div>
    </div>
        
    <hr />
    <p>Hello World, I'm Paul, and this is my final project for <a href="http://dwa15.com/" target="_blank">CSCI E-15 Dynamic Web Applications</a>. This site is built using the <a href="http://laravel.com/">Laravel</a> framework.</p>
</div>
@stop
