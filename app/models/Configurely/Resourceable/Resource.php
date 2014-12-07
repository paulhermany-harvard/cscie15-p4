<?php

namespace Configurely;

/**
 * Provides a base model for resourceable types
**/
class Resource extends Base {

    public $timestamps = false;

    public function setting() {
        return $this->morphOne('Configurely\Setting', 'resourceable');
    }

    public function delete() {
        return parent::delete();
    }
    
    public function getStringValue() {
        return $this->value;
    }
    
    public function getTypeAttribute() {
        return strtolower(preg_replace('/(Configurely\\\)(\w+)(Resource)/', '${2}', get_class($this)));
    }
    
    public function render() {
        return \View::make('resourceable.resource')
            ->with('setting', $this->setting);
    }
    
    public function setValue($setting) {
        return false;
    }
}