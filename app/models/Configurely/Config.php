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
    
    public static function getResponse($app_id, $config_id, $fn) {
        try {
			$config = Config::with('app', 'settings')->findOrFail($config_id);
		} catch(Exception $e) {
            return \ApiBaseController::getErrorResponse('ConfigController@index', [$app_id], \Lang::get('api.config_not_found'));
		}
        
        if($config->app->id != $app_id) {
            return \ApiBaseController::getErrorResponse('AppController@index', [], \Lang::get('api.bad_request'));
        }
        
        if(!\Session::get('apiuser')->owns($config->app)) {
            return \ApiBaseController::getErrorResponse('AppController@index', [$app_id], \Lang::get('api.app_not_authorized'));
        }
        
        return $fn($config);
    }
    
}