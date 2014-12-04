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
}