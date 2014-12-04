<?php

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