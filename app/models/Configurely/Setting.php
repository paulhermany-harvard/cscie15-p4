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
                'type' => 'in:binary,boolean,datetime,integer,string'
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
    
    public static function getResponse($app_id, $config_id, $setting_id, $fn) {
        try {
			$setting = Setting::with('config.app', 'resourceable')->findOrFail($setting_id);
		} catch(Exception $e) {
            return \ApiBaseController::getErrorResponse('SettingController@index', [$app_id, $config_id], \Lang::get('api.setting_not_found'));
		}
        
        if($setting->config->id != $config_id || $setting->config->app->id != $app_id) {
            return \ApiBaseController::getErrorResponse('AppController@index', [], \Lang::get('api.bad_request'));
        }
        
        if(!\Session::get('apiuser')->owns($setting->config->app)) {
            return \ApiBaseController::getErrorResponse('AppController@index', [], \Lang::get('api.app_not_authorized'));
        }
        
        return $fn($setting);
    }
}