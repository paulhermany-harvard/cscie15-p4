<?php

namespace Configurely;

class Setting extends Base {

    public function config() {
        return $this->belongsTo('Configurely\Config');
    }

    public function resourceable() {
        return $this->morphTo();
    }
    
}