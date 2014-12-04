<?php

class SettingController extends \ApiBaseController {

	/**
     * Gets a response containing the listing of settings for the specified configuration
	 *
     * @param   int      $app_id        the unique identifier of the application
     * @param   int      $config_id     the unique identifier of the configuration
	 * @return an html response if the request format is html, a json response otherwise
	**/
	public function index($app_id, $config_id) {
        try {
			$config = Configurely\Config::with('app', 'settings')->findOrFail($config_id);
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
            return View::make('setting.index')
                ->with('config', $config);
        }
 
        return Response::json($config->settings);
	}
    
	/**
	 * Gets a response containing the form for creating a new setting for the specified configuration
	 *
     * @param   int      $app_id        the unique identifier of the application
     * @param   int      $config_id     the unique identifier of the configuration
	 * @return an html response
	**/
	public function create($app_id, $config_id) {
        try {
			$config = Configurely\Config::with('app', 'settings')->findOrFail($config_id);
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
            return View::make('setting.create')
                ->with('config', $config);
        }
        // TODO: decide what to do if a json request is made to the create method
	}

	/**
	 * Stores a new setting in the database
     * 
	 * @param   int      $app_id        the unique identifier of the application
     * @param   int      $config_id     the unique identifier of the configuration
	 * @return an html response if the request format is html, a json response otherwise
	**/
	public function store($app_id, $config_id) {
        try {
			$config = Configurely\Config::with('app', 'settings')->findOrFail($config_id);
		} catch(Exception $e) {
            return $this->getErrorResponse('ConfigController@index', [$app_id], Lang::get('api.config_not_found'));
		}
        
        if($config->app->id != $app_id) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
        }
        
        if(!$this->user()->owns($config->app)) {
            return $this->getErrorResponse('AppController@index', [$app_id], Lang::get('api.app_not_authorized'));
        }
        
    /*
		$rules = array(
			'binary_value' => 'max:1000',
            'boolean_value' => 'boolean'
		);
        
		$validator = Validator::make(Input::all(), $rules);
		
		if (!$validator->passes()) {
            return View::make('setting.create')
                ->with('config', $config)
                ->withErrors($validator);
        }
    */
    
        try {
            $key = Input::get('key');
            $type = Input::get('type');
            
            switch ($type) {
                case "binary":
                
                    $resource = new Configurely\BinaryResource();
                    $resource->validate(Input::all());
                
                    if (!Input::hasFile('binary_value')) {
                        return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
                    }
                    
                    $file = Input::file('binary_value');
                    
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension(); 
                    
                    $destinationPath = '/uploads/'.$this->user()->id.'/'.$app_id.'/'.$config_id;
                    $filename = preg_replace('/[^a-zA-Z0-9-_\.]/','_', $key).'.'.$extension;
                    
                    $file->move(storage_path().$destinationPath, $filename);
                    
                    $resource->value = $destinationPath.'/'.$filename;                    
                    
                    break;
                case "boolean":
                    $resource = new Configurely\BooleanResource();
                    $resource->value = (Input::get('boolean_value') === 'yes');
                    break;
                case "float":
                    $resource = new Configurely\FloatResource();
                    $resource->value = Input::get('float_value');
                    break;
                case "integer":
                    $resource = new Configurely\IntegerResource();
                    $resource->value = Input::get('integer_value');
                    break;
                case "string":
                    $resource = new Configurely\StringResource();
                    $resource->value = Input::get('string_value');
                    break;
                default:
                    return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
            }
        
            $setting = new Configurely\Setting();
            $setting->key = $key;
            $setting->config()->associate($config);
            $setting->save();
            
            $resource->save();
            $resource->setting()->save($setting);
            
        } catch(Exception $e) {
            return $this->getErrorResponse('SettingController@index', [$app_id, $config_id], $e.' '.Lang::get('api.setting_creation_failed'));
        }

        return $this->getSuccessResponse('SettingController@index', [$app_id, $config_id], Lang::get('api.setting_created'));
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
