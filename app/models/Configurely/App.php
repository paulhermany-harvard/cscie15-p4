<?php

namespace Configurely;

class App extends Base {

    protected $fillable = array('name', 'description', 'live_url', 'scm_url');
    
    public function user() {
        return $this->belongsTo('User');
    }

    public function configs() {
        return $this->hasMany('Configurely\Config');
    }
    
    public function delete() {
        $this->configs()->delete();
        return parent::delete();
    }
    
}