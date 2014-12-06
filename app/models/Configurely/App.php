<?php

namespace Configurely;

class App extends Base {

    protected $fillable = array('name', 'description', 'live_url', 'scm_url');

    protected $hidden = array('user_id', 'configs', 'user');
    
    protected $appends = array('configs_link');

    public function getConfigsLinkAttribute() {
        return \URL::action('ConfigController@index', $this->id);
    }
    
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