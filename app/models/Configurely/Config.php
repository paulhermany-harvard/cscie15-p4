<?php

namespace Configurely;

class Config extends Base {

    protected $fillable = array('name', 'description');

    protected $hidden = array('app_id', 'app', 'settings');
    
    protected $appends = array('app_link', 'settings_link');

    public function getAppLinkAttribute() {
        return \URL::action('AppController@show', $this->app->id);
    }
    
    public function getSettingsLinkAttribute() {
        return \URL::action('SettingController@index', [$this->app->id, $this->id]);
    }
    
    public function app() {
        return $this->belongsTo('Configurely\App');
    }

    public function settings() {
        return $this->hasMany('Configurely\Setting');
    }
    
    public function delete() {
        $this->settings()->delete();
        return parent::delete();
    }
    
}