<?php

class ConfigController extends \ApiBaseController {

	/**
     * Gets a response containing the listing of configurations for the specified application
	 *
     * @param   int      $app_id        the unique identifier of the application
	 * @return an html response if the request format is html, a json response otherwise
	**/
	public function index($app_id) {
        try {
			$app = Configurely\App::with('configs')->findOrFail($app_id);
		} catch(Exception $e) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_found'));
		}
        
        if(!$this->user()->owns($app)) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_authorized'));
        }
        
        if($this->getRequestFormat() == 'html') {
            return View::make('config.index')
                ->with('app', $app);
        }
 
        return Response::json($app->configs);
	}
    
	/**
	 * Gets a response containing the form for creating a new configuration for the specified application
	 *
     * @param   int      $app_id        the unique identifier of the application
	 * @return an html response
	**/
	public function create($app_id) {
        try {
			$app = Configurely\App::findOrFail($app_id);
		} catch(Exception $e) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_found'));
		}
        
        if(!$this->user()->owns($app)) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_authorized'));
        }
        
        if($this->getRequestFormat() == 'html') {
            return View::make('config.create')
                ->with('app', $app);
        }
        // TODO: decide what to do if a json request is made to the create method
	}

	/**
	 * Stores a new configuration in the database
     * 
	 * @param   int      $app_id        the unique identifier of the application
	 * @return an html response if the request format is html, a json response otherwise
	**/
	public function store($app_id) {
        try {
			$app = Configurely\App::findOrFail($app_id);
		} catch(Exception $e) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_found'));
		}
        
        if(!$this->user()->owns($app)) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_authorized'));
        }
    
        try {
            $config = new Configurely\Config();
            $config->fill(Input::All());
            $config->app()->associate($app);
            $config->save();
        } catch(Exception $e) {
            return $this->getErrorResponse('ConfigController@index', [$app->id], Lang::get('api.config_creation_failed'));
        }

        return $this->getSuccessResponse('ConfigController@index', [$app->id], Lang::get('api.config_created'));
	}
    
	/**
	 * Gets a response containing the specified configuration
     *
	 * @param   int      $app_id        the unique identifier of the application
     * @param   int      $config_id     the unique identifier of the configuration
	 * @return  an html response if the request format is html, a json response otherwise
	**/
	public function show($app_id, $config_id) {
        try {
			$config = Configurely\Config::with('app')->findOrFail($config_id);
		} catch(Exception $e) {
            return $this->getErrorResponse('ConfigController@index', [$app_id], Lang::get('api.config_not_found'));
		}
        
        if($config->app->id != $app_id) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
        }
        
        if(!$this->user()->owns($config->app)) {
            return $this->getErrorResponse('AppController@index', [$app_id], Lang::get('api.app_not_authorized'));
        }
        
        if($this->getRequestFormat() == 'html') {
            return View::make('config.show')
                ->with('config', $config);
        }
        
        return Response::json($config);
	}


	/**
	 * Gets a response containing the form for editing an existing configuration
	 *
	 * @param   int      $app_id        the unique identifier of the application
     * @param   int      $config_id     the unique identifier of the configuration
	 * @return an html response if the request format is html, a json response otherwise
	**/
	public function edit($app_id, $config_id) {
        try {
			$config = Configurely\Config::with('app')->findOrFail($config_id);
		} catch(Exception $e) {
            return $this->getErrorResponse('ConfigController@index', [$app_id], Lang::get('api.config_not_found'));
		}
        
        if($config->app->id != $app_id) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
        }
        
        if(!$this->user()->owns($config->app)) {
            return $this->getErrorResponse('AppController@index', [$app_id], Lang::get('api.app_not_authorized'));
        }
 
        if($this->getRequestFormat() == 'html') {
            return View::make('config.edit')
                ->with('config', $config);
        }
        // TODO: decide what to do if a json request is made to the edit method
	}

	/**
	 * Updates an existing configuration in the database
	 *
     * @param   int      $app_id        the unique identifier of the application
     * @param   int      $config_id     the unique identifier of the configuration
	 * @return an html response if the request format is html, a json response otherwise
	 */
	public function update($app_id, $config_id) {
        try {
			$config = Configurely\Config::with('app')->findOrFail($config_id);
		} catch(Exception $e) {
            return $this->getErrorResponse('ConfigController@index', [$app_id], Lang::get('api.config_not_found'));
		}
        
        if($config->app->id != $app_id) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
        }
        
        if(!$this->user()->owns($config->app)) {
            return $this->getErrorResponse('AppController@index', [$app_id], Lang::get('api.app_not_authorized'));
        }
        
        try {
            $config->fill(Input::All());
            $config->save();
        } catch(Exception $e) {
            return $this->getErrorResponse('ConfigController@show', [$app_id, $config_id], Lang::get('api.config_update_failed'));
        }
        
        return $this->getSuccessResponse('ConfigController@show', [$app_id, $config_id], Lang::get('api.config_updated'));
	}

	/**
	 * Deletes an existing configuration from the database
	 *
	 * @param   int      $app_id        the unique identifier of the application
     * @param   int      $config_id     the unique identifier of the configuration
	 * @return an html response if the request format is html, a json response otherwise
	 */
	public function destroy($app_id, $config_id) {
        try {
			$config = Configurely\Config::with('app')->findOrFail($config_id);
		} catch(Exception $e) {
            return $this->getErrorResponse('ConfigController@index', [$app_id], Lang::get('api.config_not_found'));
		}
        
        if($config->app->id != $app_id) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
        }
        
        if(!$this->user()->owns($config->app)) {
            return $this->getErrorResponse('AppController@index', [$app_id], Lang::get('api.app_not_authorized'));
        }
        
        try {
            Configurely\Config::destroy($config_id);
        } catch(Exception $e) {
            return $this->getErrorResponse('ConfigController@index', [$app_id], Lang::get('api.config_delete_failed'));
        }
		
        return $this->getSuccessResponse('ConfigController@index', [$app_id], Lang::get('api.config_deleted'));
	}

}
