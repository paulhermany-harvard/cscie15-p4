<?php

namespace Configurely;

class Resource extends Base {
    public $timestamps = false;
    
    public function setting() {
        return $this->morphOne('Configurely\Setting', 'resourceable');
    }

}