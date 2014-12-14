<?php

namespace Configurely;

/**
 * This class provides a model for a Configurely application.
 * 
 * @property-read int $id the unique identifier of the application
 * @property string $name the application name (non-unique)
 * @property string $description the application description
 * @property string $live_url the url to the live or demo version of the application
 * @property string $scm_url the url to the SCM repository (git, svn, etc)
 * @property-read \DateTime $created_at the timestamp for when the application was created
 * @property-read \DateTime $updated_at the timestamp for when the application was last updated
 * @property-read string $configs_link a uri to list the configurations for the application
*/
class App extends Base {

    /**
     * provides a list of properties that should be included in the object's array and json output
    */
    protected $visible = array(
        'id',
        'name',
        'description',
        'live_url',
        'scm_url',
        'created_at',
        'updated_at',
        'configs_link'
    );

    /**
     * provides a list of custom properties that should be appended to the object's array and json output
    */
    protected $appends = array('configs_link');
    
    /**
     * provides a list of properties to explicitly allow for mass assignment
    */
    protected $fillable = array('name', 'description', 'live_url', 'scm_url');

    /**
     * User (Eloquent)
     * the user to which the application belongs
    */
    public function user() {
        return $this->belongsTo('User');
    }    

    /**
     * Configs
     * the list of configurations for the application
    */
    public function configs() {
        return $this->hasMany('Configurely\Config');
    }
    
    /**
     * Configs Link
     * gets the uri to list the configurations for the application
    */
    public function getConfigsLinkAttribute() {
        return \URL::action('ConfigController@index', $this->id);
    }
    
    /**
     * gets the validation rules for the model
    */
    protected function rules() {
        return array_merge(
            parent::rules(),
            array(
                'name' => ['max:255', 'required'],
                'description' => 'max:4000',
                'live_url' => ['max:255', 'url'],
                'scm_url' => ['max:255', 'url']
            )
        );
    }
    
    /**
     * gets the breadcrumbs for the model
    */
    public function breadcrumbs() {
        return array(
            ['Applications' => \URL::action('AppController@index')],
            '/',
            [e($this->name) => \URL::action('AppController@show', $this->id)]
        );
    }
    
    /**
     * deletes the configurations for the application and then deletes the application
    */
    public function delete() {
        $this->configs()->delete();
        return parent::delete();
    }
    
    /**
     * gets an application identified by the specified id and calls the delegate to get the response.
    */
    public static function getResponse($app_id, $fn) {
        try {
			$app = App::with('configs')->findOrFail($app_id);
		} catch(Exception $e) {
            return \ApiBaseController::getErrorResponse('AppController@index', [], \Lang::get('api.app_not_found'));
		}
        
        if(!\Auth::user()->owns($app)) {
            return \ApiBaseController::getErrorResponse('AppController@index', [], \Lang::get('api.app_not_authorized'));
        }
        
        return $fn($app);
    }
}