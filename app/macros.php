<?php


/**
 * Form::control macro
 *
 * This macro creates a templated control
 *
 * @param  string  $type
 * @param  string  $name
 * @param  string  $text
 * @param  mixed   $value
 * @param  array   $errors
 * @param  array   $options
 * @return string
 */
Form::macro('control', function($type = 'text', $name, $text, $value = null, $errors = null, $options = array()) {
    $options = array_merge_recursive(array('class' => 'form-control'), $options);
    foreach ((array) $options as $k => $v) {
		if(is_array($v)) { $options[$k] = implode(' ', $v); }
    }
    return View::make('controls.'.$type)
        ->with('name', $name)
        ->with('text', $text)
        ->with('value', $value)
        ->with('errors', $errors)
        ->with('options', $options);
});

/**
 * Form::link macro
 * 
 * This macro creates a hyperlink for a form
 *
 * @param  string   $text           The text value of the link
 * @param  string   $value          The hyperlink location (href)
 * @param  array    $options        The array of attributes to apply to the link
 * @return string
 */
Form::macro('link', function($text, $value, $options = array()) {
    return link_to($value, $text, $options);
});


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

/**
 * Form::timestamp macro
 * 
 * This macro creates a disabled text field with a UTC-formatted timestamp (created_at or updated_at)
 *
 * @param  string   $name           The name of the text field
 * @param  DateTime $value          The default value of the text field
 * @param  array    $options        The array of attributes to apply to the text field
 * @return string
 */
Form::macro('timestamp', function($name, $value = null, $options = array()) {
    $options = array_merge_recursive(
        array(
            'class' => 'date utc',
            'disabled' => 'disabled'
        ),
        $options
    );
    foreach ((array) $options as $k => $v) {
		if(is_array($v)) {
            $options[$k] = implode(' ', $v);
        }
    }
    return Form::text($name, isset($value) ? $value->format('c') : '', $options);
});

HTML::macro('breadcrumbs', function($before = array(), $current = array(), $after = array()) {
    $breadcrumbs = array();
    
    // add 'before' crumbs
    if(count($before) > 0) {
        $breadcrumbs = array_merge($breadcrumbs, $before);
    }
    
    // add model crumbs
    if(count($current) > 0) {
        // add separator
        if(count($breadcrumbs) > 0) { 
            array_push($breadcrumbs, '/');
        }
        $breadcrumbs = array_merge($breadcrumbs, $current);
    }
    
    // add 'after' crumbs
    if(count($after) > 0) {
        // add separator
        if(count($breadcrumbs) > 0) {
            array_push($breadcrumbs, '/');
        }
        $breadcrumbs = array_merge($breadcrumbs, $after);
    }

    // generate breadcrumb control
    return View::make('controls.breadcrumbs')
        ->with('breadcrumbs', $breadcrumbs);
});

HTML::macro('confirm_delete', function($title, $message) {
    return View::make('dialogs.confirm_delete')
        ->with('title', $title)
        ->with('message', $message);
});

HTML::macro('resource', function($resource) { 
    return $resource->render();
});

