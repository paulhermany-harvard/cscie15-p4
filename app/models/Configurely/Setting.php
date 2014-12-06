<?php

namespace Configurely;

/**
 * Provides a model for application configuration settings
**/
class Setting extends Base {

    protected $hidden = array('config_id', 'config', 'resourceable_id', 'resourceable_type', 'resourceable');
    protected $appends = array('type', 'value', 'app_link', 'config_link');

    protected function rules() {
        return array_merge(
            parent::rules(),
            array(
                'key' => array(
                    'unique:settings,key,'.$this->id,
                    'required'
                ),
                'type' => 'in:binary,boolean,datetime,float,integer,string'
            )
        );
    }
    
    public function getAppLinkAttribute() {
        return \URL::action('AppController@show', $this->config->app->id);
    }
    
    public function getConfigLinkAttribute() {
        return \URL::action('ConfigController@show', [$this->config->app->id, $this->config->id]);
    }
    
    public function getValueAttribute() {
        return $this->resourceable->getStringValue();
    }
    
    public function getTypeAttribute() {
        return $this->resourceable->getTypeAttribute();
    }
    
    public function config() {
        return $this->belongsTo('Configurely\Config');
    }
    
    public function resourceable() {
        return $this->morphTo();
    }
    
    public function delete() {
        $this->resourceable->delete();
        return parent::delete();
    }
    
}