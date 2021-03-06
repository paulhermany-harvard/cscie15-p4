<?php

namespace Configurely;

/**
 * This class provides a model for a Configurely application configuration setting.
 * 
 * @property-read int $id the unique identifier of the setting
 * @property string $key the setting key (unique)
 * @property-read \DateTime $created_at the timestamp for when the setting was created
 * @property-read \DateTime $updated_at the timestamp for when the setting was last updated
 * @property-read string $type the data type of the resource
 * @property-read mixed $value the value of the resource
 * @property-read string $app_link a uri to view the application to which the setting's configuration belongs
 * @property-read string $config_link a uri to view the configuration to which the setting belongs
*/
class Setting extends Base {

    /**
     * provides a list of properties that should be included in the object's array and json output
    */
    protected $visible = array(
        'id',
        'key',
        'created_at',
        'updated_at',
        'type',
        'value',
        'app_link',
        'config_link'
    );
    
    /**
     * provides a list of custom properties that should be appended to the object's array and json output
    */
    protected $appends = array('type', 'value', 'app_link', 'config_link');

    /**
     * Config (Eloquent)
     * the configuration to which the setting belongs
    */
    public function config() {
        return $this->belongsTo('Configurely\Config');
    }
    
    /**
     * Resourceable (Eloquent)
     * the resource containing the value of the setting
    */
    public function resourceable() {
        return $this->morphTo();
    }

    /**
     * Type
     * the data type of the resource
    */
    public function getTypeAttribute() {
        return $this->resourceable->getTypeAttribute();
    }

    /**
     * Value
     * the value of the resource
    */    
    public function getValueAttribute() {
        return $this->resourceable->getStringValue();
    }
    
    /**
     * App Link
     * gets the uri to view the application to which the setting's configuration belongs
    */
    public function getAppLinkAttribute() {
        return \URL::action('AppController@show', $this->config->app->id);
    }
    
    /**
     * Config Link
     * gets the uri to view the configuration to which the setting belongs
    */
    public function getConfigLinkAttribute() {
        return \URL::action('ConfigController@show', [$this->config->app->id, $this->config->id]);
    }
    
    /**
     * gets the validation rules for the model
    */
    protected function rules() {
        $setting_id = $this->id ? $this->id : 0;
        $config_id = $this->config->id;
        return array_merge(
            parent::rules(),
            array(
                // the key is required and is unique to the configuration
                'key' => ['max:255', 'unique:settings,key,'.$setting_id.',id,config_id,'.$config_id, 'required'],
                'type' => 'in:binary,boolean,datetime,integer,string'
            )
        );
    }
    
    /**
     * gets the breadcrumbs for the model
    */
    public function breadcrumbs() {
        $breadcrumbs = $this->config->breadcrumbs();
        if(count($breadcrumbs) > 0) {
            array_push($breadcrumbs, '/');
        }
        return array_merge(
            $breadcrumbs,
            array(
                ['Settings' => \URL::action('SettingController@index', [$this->config->app->id, $this->config->id])],
                '/',
                [e($this->key) => \URL::action('SettingController@show', [$this->config->app->id, $this->config->id, $this->id])]
            )
        );
    }
    
    /**
     * deletes the resource for the setting and then deletes the setting
    */
    public function delete() {
        $this->resourceable->delete();
        return parent::delete();
    }

    /**
     * gets a setting identified by the specified id and calls the delegate to get the response.
    */
    public static function getResponse($app_id, $config_id, $setting_id, $fn) {
        try {
			$setting = Setting::with('config.app', 'resourceable')->findOrFail($setting_id);
		} catch(Exception $e) {
            return \ApiBaseController::getErrorResponse('SettingController@index', [$app_id, $config_id], \Lang::get('api.setting_not_found'));
		}
        
        if($setting->config->id != $config_id || $setting->config->app->id != $app_id) {
            return \ApiBaseController::getErrorResponse('AppController@index', [], \Lang::get('api.bad_request'));
        }
        
        if(!\Auth::user()->owns($setting->config->app)) {
            return \ApiBaseController::getErrorResponse('AppController@index', [], \Lang::get('api.app_not_authorized'));
        }
        
        return $fn($setting);
    }
}