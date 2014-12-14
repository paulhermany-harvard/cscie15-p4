<?php

namespace Configurely;

/**
 * This class provides a model for a Configurely application configuration.
 * 
 * @property-read int $id the unique identifier of the configuration
 * @property string $name the configuration name (non-unique)
 * @property string $description the configuration description
 * @property-read \DateTime $created_at the timestamp for when the configuration was created
 * @property-read \DateTime $updated_at the timestamp for when the configuration was last updated
 * @property-read string $app_link a uri to view the application to which the configuration belongs
 * @property-read string $settings_link a uri to list the settings for the configuration
*/
class Config extends Base {

    /**
     * provides a list of properties that should be included in the object's array and json output
    */
    protected $visible = array(
        'id',
        'name',
        'description',
        'created_at',
        'updated_at',
        'app_link',
        'settings_link'
    );

    /**
     * provides a list of custom properties that should be appended to the object's array and json output
    */
    protected $appends = array('app_link', 'settings_link');

    /**
     * provides a list of properties to explicitly allow for mass assignment
    */
    protected $fillable = array('name', 'description');

    /**
     * App (Eloquent)
     * the app to which the configuration belongs
    */
    public function app() {
        return $this->belongsTo('Configurely\App');
    }

    /**
     * Settings (Eloquent)
     * the list of settings for the configuration
    */
    public function settings() {
        return $this->hasMany('Configurely\Setting');
    }
    
    /**
     * App Link
     * gets the uri to view the application to which the configuration belongs
    */
    public function getAppLinkAttribute() {
        return \URL::action('AppController@show', $this->app->id);
    }

    /**
     * Settings Link
     * gets the uri to list the settings for the application
    */    
    public function getSettingsLinkAttribute() {
        return \URL::action('SettingController@index', [$this->app->id, $this->id]);
    }
    
    /**
     * gets the validation rules for the model
    */
    protected function rules() {
        return array_merge(
            parent::rules(),
            array(
                'name' => ['max:255', 'required'],
                'description' => 'max:4000'
            )
        );
    }
    
    /**
     * deletes the settings for the configuration and then deletes the configuration
    */
    public function delete() {
        $this->settings()->delete();
        return parent::delete();
    }
    
    /**
     * gets the breadcrumbs for the model
    */
    public function breadcrumbs() {
        $breadcrumbs = $this->app->breadcrumbs();
        if(count($breadcrumbs) > 0) {
            array_push($breadcrumbs, '/');
        }
        return array_merge(
            $breadcrumbs,
            array(
                ['Configurations' => \URL::action('ConfigController@index', $this->app->id)],
                '/',
                [e($this->name) => \URL::action('ConfigController@show', [$this->app->id, $this->id])]
            )
        );
    }
    
    /**
     * gets a configuration identified by the specified id and calls the delegate to get the response.
    */
    public static function getResponse($app_id, $config_id, $fn) {
        try {
			$config = Config::with('app', 'settings')->findOrFail($config_id);
		} catch(Exception $e) {
            return \ApiBaseController::getErrorResponse('ConfigController@index', [$app_id], \Lang::get('api.config_not_found'));
		}
        
        if($config->app->id != $app_id) {
            return \ApiBaseController::getErrorResponse('AppController@index', [], \Lang::get('api.bad_request'));
        }
        
        if(!\Auth::user()->owns($config->app)) {
            return \ApiBaseController::getErrorResponse('AppController@index', [$app_id], \Lang::get('api.app_not_authorized'));
        }
        
        return $fn($config);
    }
}