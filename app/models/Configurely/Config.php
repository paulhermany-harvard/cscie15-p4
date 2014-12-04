<?php

namespace Configurely;

class Config extends Base {

    protected $fillable = array('name', 'description');

    public function app() {
        return $this->belongsTo('Configurely\App');
    }

    public function settings() {
        return $this->hasMany('Configurely\Setting');
    }
    
}