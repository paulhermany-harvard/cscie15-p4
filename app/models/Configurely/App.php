<?php

namespace Configurely;

/**
 * App model
 * 
 * {
 *   "id":int,
 *   "name":string,
 *   "description":string,
 *   "live_url":string,
 *   "scm_url":string,
 *   "created_at":timestamp,
 *   "updated_at":timestamp,
 *   "configs_link":string
 * }
**/
class App extends Base {

    protected $visible = array(
    
        /**
         * Id
         * the unique identifier of the application
        **/
        'id',
        
        /**
         * Name
         * the application name (non-unique)
        **/
        'name',
        
        /**
         * Description
         * the application description
        **/
        'description',
        
        /**
         * Live Url
         * the url to the live or demo version of the application
        **/
        'live_url',
        
        /**
         * Source Code Management (SCM) Url
         * the url to the SCM repository (git, svn, etc)
        **/
        'scm_url',
        
        /**
         * Created At
         * the timestamp for when the application was created
        **/
        'created_at',
        
        /**
         * Updated At
         * the timestamp for when the application was last updated
        **/
        'updated_at',
        
        /**
         * Configs Link
         * a uri string to list the configurations for the application
        **/
        'configs_link'
        
    );
    
    protected $appends = array('configs_link');
    
    protected $fillable = array('name', 'description', 'live_url', 'scm_url');

    /**
     * User (Eloquent)
     * the user to which the application belongs
    **/
    public function user() {
        return $this->belongsTo('User');
    }    

    /**
     * Configs
     * the list of configurations for the application
    **/
    public function configs() {
        return $this->hasMany('Configurely\Config');
    }
    
    /**
     * Gets the uri string to list the configurations for the application
    **/
    public function getConfigsLinkAttribute() {
        return \URL::action('ConfigController@index', $this->id);
    }

    /**
     * Deletes the configurations for the application and then deletes the application
    **/
    public function delete() {
        $this->configs()->delete();
        return parent::delete();
    }
    
    /**
     * Gets an application identified by the specified id and
     *   calls the delegate to get the response.
    **/
    public static function getResponse($app_id, $fn) {
        try {
			$app = Configurely\App::with('configs')->findOrFail($app_id);
		} catch(Exception $e) {
            return \ApiBaseController::getErrorResponse('AppController@index', [], \Lang::get('api.app_not_found'));
		}
        
        if(!\Session::get('apiuser')->owns($app)) {
            return \ApiBaseController::getErrorResponse('AppController@index', [], \Lang::get('api.app_not_authorized'));
        }
        
        return $fn($app);
    }
}