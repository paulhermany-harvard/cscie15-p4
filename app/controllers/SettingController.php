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
    
        try {
            $key = Input::get('key');
            $type = Input::get('type');
            
            $class = 'Configurely\\'.ucfirst($type).'Resource';

            $resource = new $class();
            if(!$resource->validate(Input::all())) {
                return Redirect::action('SettingController@create', [$app_id, $config_id])
                    ->withInput()
                    ->withErrors($resource->validator());
            }
            
            switch ($type) {
                case "binary":
                    $value = Configurely\BinaryResource::saveFile(Input::file('binary_value'), $key, $this->user()->id, $app_id, $config_id); break;
                case "boolean":
                    $value = (Input::get('boolean_value') === 'yes'); break;
                case "datetime":
                    $value = Input::get('datetime_value'); break;
                case "float":
                    $value = Input::get('float_value'); break;
                case "integer":
                    $value = Input::get('integer_value'); break;
                case "string":
                    $value = Input::get('string_value'); break;
                default:
                    return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
            }
            
            $resource->value = $value;
            $resource->save();
        
            $setting = new Configurely\Setting();
            $setting->key = $key;
            $setting->config()->associate($config);
            $setting->save();
            
            $resource->setting()->save($setting);
            
        } catch(Exception $e) {
            return $this->getErrorResponse('SettingController@index', [$app_id, $config_id], $e.' '.Lang::get('api.setting_creation_failed'));
        }

        return $this->getSuccessResponse('SettingController@index', [$app_id, $config_id], Lang::get('api.setting_created'));
	}
    
	/**
	 * Gets a response containing the specified setting
     *
	 * @param   int      $app_id        the unique identifier of the application
     * @param   int      $config_id     the unique identifier of the configuration
     * @param   int      $setting_id    the unique identifier of the setting
	 * @return  an html response if the request format is html, a json response otherwise
	**/
	public function show($app_id, $config_id, $setting_id) {
        try {
			$setting = Configurely\Setting::with('config.app', 'resourceable')->findOrFail($setting_id);
		} catch(Exception $e) {
            return $this->getErrorResponse('SettingController@index', [$app_id, $config_id], Lang::get('api.setting_not_found'));
		}
        
        if($setting->config->id != $config_id || $setting->config->app->id != $app_id) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
        }
        
        if(!$this->user()->owns($setting->config->app)) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_authorized'));
        }
        
        if($this->getRequestFormat() == 'html') {
            return View::make('setting.show')
                ->with('setting', $setting);
        }
        
        return Response::json($setting);
	}


	/**
	 * Gets a response containing the form for editing an existing setting
	 *
	 * @param   int      $app_id        the unique identifier of the application
     * @param   int      $config_id     the unique identifier of the configuration
     * @param   int      $setting_id    the unique identifier of the setting
	 * @return an html response if the request format is html, a json response otherwise
	**/
	public function edit($app_id, $config_id, $setting_id) {
        try {
			$setting = Configurely\Setting::with('config.app', 'resourceable')->findOrFail($setting_id);
		} catch(Exception $e) {
            return $this->getErrorResponse('SettingController@index', [$app_id, $config_id], Lang::get('api.setting_not_found'));
		}
        
        if($setting->config->id != $config_id || $setting->config->app->id != $app_id) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
        }
        
        if(!$this->user()->owns($setting->config->app)) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_authorized'));
        }
 
        if($this->getRequestFormat() == 'html') {
            return View::make('setting.edit')
                ->with('setting', $setting);
        }
        // TODO: decide what to do if a json request is made to the edit method
	}

	/**
	 * Updates an existing configuration in the database
	 *
     * @param   int      $app_id        the unique identifier of the application
     * @param   int      $config_id     the unique identifier of the configuration
     * @param   int      $setting_id    the unique identifier of the setting
	 * @return an html response if the request format is html, a json response otherwise
	 */
	public function update($app_id, $config_id, $setting_id) {
        try {
			$setting = Configurely\Setting::with('config.app', 'resourceable')->findOrFail($setting_id);
		} catch(Exception $e) {
            return $this->getErrorResponse('SettingController@index', [$app_id, $config_id], Lang::get('api.setting_not_found'));
		}
        
        if($setting->config->id != $config_id || $setting->config->app->id != $app_id) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
        }
        
        if(!$this->user()->owns($setting->config->app)) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_authorized'));
        }
        
        try {
            $key = Input::get('key');
            $type = Input::get('type');
            
            $class = 'Configurely\\'.ucfirst($type).'Resource';

            $resource = new $class();
            
            if(!$setting->validate(Input::all())) {
                return Redirect::action('SettingController@edit', [$app_id, $config_id, $setting_id])
                    ->withInput()
                    ->withErrors($setting->validator());
            }
            
            if(!$resource->validate(Input::all())) {
                return Redirect::action('SettingController@edit', [$app_id, $config_id, $setting_id])
                    ->withInput()
                    ->withErrors($resource->validator());
            }
            
            switch ($type) {
                case "binary":
                    $value = Configurely\BinaryResource::saveFile(Input::file('binary_value'), $key, $this->user()->id, $app_id, $config_id); break;
                case "boolean":
                    $value = (Input::get('boolean_value') === 'yes'); break;
                case "datetime":
                    $value = Input::get('datetime_value'); break;
                case "integer":
                    $value = Input::get('integer_value'); break;
                case "string":
                    $value = Input::get('string_value'); break;
                default:
                    return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
            }
            
            $resource->value = $value;
            $resource->save();
        
            $setting->resourceable->delete();
            $setting->key = $key;
            $setting->save();
            
            $resource->setting()->save($setting);
        
        } catch(Exception $e) {
            return $this->getErrorResponse('SettingController@show', [$app_id, $config_id, $setting_id], $e.' '.Lang::get('api.setting_update_failed'));
        }
        
        return $this->getSuccessResponse('SettingController@show', [$app_id, $config_id, $setting_id], Lang::get('api.setting_updated'));
	}

	/**
	 * Deletes an existing setting from the database
	 *
	 * @param   int      $app_id        the unique identifier of the application
     * @param   int      $config_id     the unique identifier of the configuration
     * @param   int      $setting_id    the unique identifier of the configuration
	 * @return an html response if the request format is html, a json response otherwise
	 */
	public function destroy($app_id, $config_id, $setting_id) {
        try {
			$setting = Configurely\Setting::with('config.app', 'resourceable')->findOrFail($setting_id);
		} catch(Exception $e) {
            return $this->getErrorResponse('SettingController@index', [$app_id, $config_id], Lang::get('api.setting_not_found'));
		}
        
        if($setting->config->id != $config_id || $setting->config->app->id != $app_id) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
        }
        
        if(!$this->user()->owns($setting->config->app)) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_authorized'));
        }
        
        try {
            Configurely\Setting::destroy($setting_id);
        } catch(Exception $e) {
            return $this->getErrorResponse('SettingController@index', [$app_id, $config_id], Lang::get('api.setting_delete_failed'));
        }
		
        return $this->getSuccessResponse('SettingController@index', [$app_id, $config_id], Lang::get('api.setting_deleted'));
	}

	/**
	 * Downloads a file from the storage folder
	 *
	 * @param   int      $app_id        the unique identifier of the application
     * @param   int      $config_id     the unique identifier of the configuration
     * @param   int      $setting_id    the unique identifier of the setting
	 * @return the file as a stream
	 */
	public function download($app_id, $config_id, $setting_id) {
        try {
			$setting = Configurely\Setting::with('config.app', 'resourceable')->findOrFail($setting_id);
		} catch(Exception $e) {
            return $this->getErrorResponse('SettingController@index', [$app_id, $config_id], Lang::get('api.setting_not_found'));
		}
        
        if($setting->config->id != $config_id || $setting->config->app->id != $app_id) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
        }
        
        if(!$this->user()->owns($setting->config->app)) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_authorized'));
        }
        
        $fileinfo = explode(';', $setting->resourceable->value);
        $filepath = str_replace('/','\\',$fileinfo[0]);
        $filename = $fileinfo[1];
        
        $path = storage_path().$filepath;
        
        if(!\File::exists($path)) {
            return $this->getErrorResponse('SettingController@show', [$app_id, $config_id, $setting_id], Lang::get('api.file_not_found'));;
        }

        return Response::make(File::get($path), 200, array(
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Content-Type' => 'application/octet-stream'
        ));
	}
    
}
