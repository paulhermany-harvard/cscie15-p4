<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

    app_path().'/commands',
    app_path().'/controllers',
    app_path().'/models',
    app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
    Log::error($exception);
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
    return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';

/**
 * Form::dropdown macro
 *
 * This macro calls the Form::select method to create a dropdown (select) list from an array of objects.	 
 *
 * @param  string  $name
 * @param  array   $list            The list of objects
 * @param  string  $valueProperty   The property of the object to use as the "value" attribute of the <option> element (default is "id")
 * @param  string  $textProperty    The property of the object to use as the text value of the <option> element (default is "name")
 * @param  string  $selected
 * @param  array   $options
 * @return string
 */
Form::macro('dropdown', function($name, $models = null, $valueProperty = 'id', $textProperty = 'name', $selected = null, $options = array()) {
    $list = array();
    
    // iterate through the list of models
    foreach ($models as $model) {
        // add the value/text to the list of key/value pairs
        $list[$model[$valueProperty]] = $model[$textProperty];
    }

    // call the Form::select macro to create the dropdown
    return Form::select($name, $list, $selected, $options);
});