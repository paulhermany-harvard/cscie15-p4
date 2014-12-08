<?php

class ConfigController extends \ApiBaseController {

	/**
     * calls the Configurely\App::getResponse method to get a response containing the listing of configurations for the specified application or an error response indicating that the configurations can not be listed as requested
	 *
     * @param int $app_id the unique identifier of the application
     * 
	 * @return Response an html response if the request format is html, a json response otherwise
	 */
	public function index($app_id) {
        return Configurely\App::getResponse($app_id,
            function($app) {
                if($this->getRequestFormat() == 'html') {
                    return View::make('config.index')
                        ->with('app', $app);
                }
                return Response::json($app->configs);
            }
        );        
	}
    
	/**
     * calls the Configurely\App::getResponse method to get a response containing the form for creating a new configuration for the specified application or an error response indicating that the configuration can not be created
	 *
     * @param int $app_id the unique identifier of the application
     * 
	 * @return Response an html response
	 */
	public function create($app_id) {
        return Configurely\App::getResponse($app_id,
            function($app) {
                if($this->getRequestFormat() == 'html') {
                    return View::make('config.create')
                        ->with('app', $app);
                }
                // TODO: decide what to do if a json request is made to the create method
            }
        );
	}

	/**
	 * calls the Configurely\App::getResponse method to get a success response indicating that the configuration is stored successfully or an error response indicating that the configuration can not be stored
     * 
	 * @param int $app_id the unique identifier of the application
     *
	 * @return Response an html response if the request format is html, a json response otherwise
	 */
	public function store($app_id) {
        return Configurely\App::getResponse($app_id,
            function($app) {
                try {
                    $config = new Configurely\Config();
                    if(!$config->validate(Input::all())) {
                        return $this->getRedirectToCreate($app, $config->validator());
                    }
                    
                    $config->fill(Input::All());
                    $config->app()->associate($app);
                    $config->save();
                } catch(Exception $e) {
                    return $this->getErrorResponse('ConfigController@index', [$app->id], Lang::get('api.config_creation_failed'));
                }
                return $this->getSuccessResponse('ConfigController@index', [$app->id], Lang::get('api.config_created'));
            }
        );
	}
    
	/**
     * calls the Configurely\Config::getResponse method to get a response containing a view of the specified configuration or an error response indicating that the configuration can not be displayed
     *
	 * @param int $app_id the unique identifier of the application
     * @param int $config_id the unique identifier of the configuration
     * 
	 * @return Response an html response if the request format is html, a json response otherwise
	 */
	public function show($app_id, $config_id) {
        return Configurely\Config::getResponse($app_id, $config_id,
            function($config) {
                if($this->getRequestFormat() == 'html') {
                    return View::make('config.show')
                        ->with('config', $config);
                }
                return Response::json($config);
            }
        );
	}


	/**
     * calls the Configurely\Config::getResponse method to get a response containing the form for editing the specified configuration or an error response indicating that the configuration can not be edited
	 *
	 * @param int $app_id the unique identifier of the application
     * @param int $config_id the unique identifier of the configuration
     *
	 * @return Response an html response if the request format is html, a json response otherwise
	**/
	public function edit($app_id, $config_id) {
        return Configurely\Config::getResponse($app_id, $config_id,
            function($config) {
                if($this->getRequestFormat() == 'html') {
                    return View::make('config.edit')
                        ->with('config', $config);
                }
                // TODO: decide what to do if a json request is made to the edit method
            }
        );
	}

	/**
     * calls the Configurely\Config::getResponse method to get a success response indicating that the specified configuration is updated or an error response indicating that the configuration can not be updated
	 *
     * @param int $app_id the unique identifier of the application
     * @param int $config_id the unique identifier of the configuration
     *
	 * @return Response an html response if the request format is html, a json response otherwise
	 */
	public function update($app_id, $config_id) {
        return Configurely\Config::getResponse($app_id, $config_id,
            function($config) {
                try {
                    if(!$config->validate(Input::all())) {
                        return $this->getRedirectToEdit($config, $config->validator());
                    }
                    
                    $config->fill(Input::All());
                    $config->save();
                } catch(Exception $e) {
                    return $this->getErrorResponse('ConfigController@show', [$app_id, $config_id], Lang::get('api.config_update_failed'));
                }
                return $this->getSuccessResponse('ConfigController@show', [$app_id, $config_id], Lang::get('api.config_updated'));
            }
        );
	}

	/**
     * calls the Configurely\Config::getResponse method to get a success response indicating that the specified configuration is deleted or an error response indicating that the configuration can not be deleted
	 *
	 * @param int $app_id the unique identifier of the application
     * @param int $config_id the unique identifier of the configuration
     * 
	 * @return Response an html response if the request format is html, a json response otherwise
	 */
	public function destroy($app_id, $config_id) {
        return Configurely\Config::getResponse($app_id, $config_id,
            function($config) {
                try {
                    Configurely\Config::destroy($config->id);
                } catch(Exception $e) {
                    return $this->getErrorResponse('ConfigController@index', [$config->app->id], Lang::get('api.config_delete_failed'));
                }                
                return $this->getSuccessResponse('ConfigController@index', [$config->app->id], Lang::get('api.config_deleted'));
            }
        );
	}
    
    /**
     * gets a response containing a redirect to the create form with input and validation errors
     * 
     * @return Response an html response
     */
    protected function getRedirectToCreate($app, $validator) {
        return Redirect::action('ConfigController@create', [$app->id])
            ->withInput()
            ->withErrors($validator);
    }

    /**
     * gets a response containing a redirect to the edit form with input and validation errors
     *
     * @return Response an html response
     */
    protected function getRedirectToEdit($config, $validator) {
        return Redirect::action('ConfigController@edit', [$config->app->id, $config->id])
            ->withInput()
            ->withErrors($validator);
    }

}
