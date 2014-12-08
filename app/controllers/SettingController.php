<?php

class SettingController extends \ApiBaseController {
    
	/**
     * calls the Configurely\Config::getResponse method to get a response containing the listing of settings for the specified configuration or an error response indicating that the settings can not be listed as requested
	 *
     * @param int $app_id the unique identifier of the application
     * @param int $config_id the unique identifier of the configuration
     * 
	 * @return Response an html response if the request format is html, a json response otherwise
	*/
	public function index($app_id, $config_id) {
        return Configurely\Config::getResponse($app_id, $config_id,
            function($config) {
                if($this->getRequestFormat() == 'html') {
                    return View::make('setting.index')
                        ->with('config', $config);
                }
                return Response::json($config->settings);
            }
        );
	}
    
	/**
	 * calls the Configurely\Config::getResponse method to get a response containing the form for creating a new setting for the specified configuration or an error response indicating that the setting can not be created
	 *
     * @param int $app_id the unique identifier of the application
     * @param int $config_id the unique identifier of the configuration
     *
	 * @return Response an html response
	 */
	public function create($app_id, $config_id) {
        return Configurely\Config::getResponse($app_id, $config_id,
            function($config) {
                if($this->getRequestFormat() == 'html') {
                    return View::make('setting.create')
                        ->with('config', $config);
                }
            }
        );
	}

	/**
	 * calls the Configurely\Config::getResponse method to get a success response indicating that the setting is stored successfully or an error response indicating that the setting can not be stored
     * 
	 * @param int $app_id the unique identifier of the application
     * @param int $config_id the unique identifier of the configuration
     * 
	 * @return Response an html response if the request format is html, a json response otherwise
	 */
	public function store($app_id, $config_id) {
        return Configurely\Config::getResponse($app_id, $config_id,
            function($config) {

                try {
                    $key = Input::get('key');
                    $type = Input::get('type');
                    
                    $class = 'Configurely\\'.ucfirst($type).'Resource';
                    if(!class_exists($class)) {
                        return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
                    }
                    
                    $setting = new Configurely\Setting();
                    if(!$setting->validate(Input::all())) {
                        return $this->getRedirectToCreate($config, $setting->validator());
                    }
                    
                    $setting->key = $key;
                    $setting->config()->associate($config);
                    $setting->save();
                    
                    $resource = new $class();
                    if(!$resource->validate(Input::all())) {
                        return $this->getRedirectToCreate($config, $resource->validator());
                    }
                    
                    if(!$resource->setValue($setting)) {
                        return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
                    }
                    $resource->save();
                    $resource->setting()->save($setting);
                    
                } catch(Exception $e) {
                    return $this->getErrorResponse('SettingController@index', [$config->app->id, $config->id], Lang::get('api.setting_creation_failed'));
                }

                return $this->getSuccessResponse('SettingController@index', [$config->app->id, $config->id], Lang::get('api.setting_created'));
            }
        );
	}
    
	/**
	 * calls the Configurely\Setting::getResponse method to get a response containing a view of the specified setting or an error response indicating that the setting can not be displayed
     *
	 * @param int $app_id the unique identifier of the application
     * @param int $config_id the unique identifier of the configuration
     * @param int $setting_id the unique identifier of the setting
     * 
	 * @return Response an html response if the request format is html, a json response otherwise
	 */
	public function show($app_id, $config_id, $setting_id) {
        return Configurely\Setting::getResponse($app_id, $config_id, $setting_id,
            function($setting) {
                if($this->getRequestFormat() == 'html') {
                    return View::make('setting.show')
                        ->with('setting', $setting);
                }
                return Response::json($setting);
            }
        );
	}

	/**
	 * calls the Configurely\Setting::getResponse method to get a response containing the form for editing the specified setting or an error response indicating that the setting can not be edited
	 *
	 * @param int $app_id the unique identifier of the application
     * @param int $config_id the unique identifier of the configuration
     * @param int $setting_id the unique identifier of the setting
     * 
	 * @return Response an html response if the request format is html, a json response otherwise
	 */
	public function edit($app_id, $config_id, $setting_id) {
        return Configurely\Setting::getResponse($app_id, $config_id, $setting_id,
            function($setting) {
                if($this->getRequestFormat() == 'html') {
                    return View::make('setting.edit')
                        ->with('setting', $setting);
                }
            }
        );
	}
    
	/**
	 * calls the Configurely\Setting::getResponse method to get a success response indicating that the specified setting is updated or an error response indicating that the setting can not be updated
     *
     * @param int $app_id the unique identifier of the application
     * @param int $config_id the unique identifier of the configuration
     * @param int $setting_id the unique identifier of the setting
     * 
	 * @return Response an html response if the request format is html, a json response otherwise
	 */
	public function update($app_id, $config_id, $setting_id) {
        return Configurely\Setting::getResponse($app_id, $config_id, $setting_id,
            function($setting) {
                try {
                    $key = Input::get('key');
                    $type = Input::get('type');
                    
                    if(!$setting->validate(Input::all())) {
                        return $this->getRedirectToEdit($setting, $setting->validator());
                    }
                    
                    // Because of the polymorphic relationship between Setting and Resource,
                    //   the existing resource will be deleted and a new resource will be created.
                    //   One way to optimize this is to only recreate the resource when the type is changed.
                    $setting->resourceable->delete();
                    $setting->key = $key;
                    $setting->save();
                    
                    $class = 'Configurely\\'.ucfirst($type).'Resource';
                    if(!class_exists($class)) {
                        return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
                    }
                    
                    $resource = new $class();
                    if(!$resource->validate(Input::all())) {
                        return $this->getRedirectToEdit($setting, $resource->validator());
                    }
                    
                    if(!$resource->setValue($setting)) {
                        return $this->getErrorResponse('AppController@index', [], Lang::get('api.bad_request'));
                    }
                    $resource->save();
                    $resource->setting()->save($setting);
                
                } catch(Exception $e) {
                    return $this->getErrorResponse('SettingController@show', [$setting->config->app->id, $setting->config->id, $setting->id], Lang::get('api.setting_update_failed'));
                }
                
                return $this->getSuccessResponse('SettingController@show', [$setting->config->app->id, $setting->config->id, $setting->id], Lang::get('api.setting_updated'));
            }
        );
	}

	/**
	 * calls the Configurely\Setting::getResponse method to get a success response indicating that the specified setting is deleted or an error response indicating that the setting can not be deleted
	 *
	 * @param int $app_id the unique identifier of the application
     * @param int $config_id the unique identifier of the configuration
     * @param int $setting_id the unique identifier of the configuration
     * 
	 * @return Response an html response if the request format is html, a json response otherwise
	 */
	public function destroy($app_id, $config_id, $setting_id) {
        return Configurely\Setting::getResponse($app_id, $config_id, $setting_id,
            function($setting) {
                try {
                    Configurely\Setting::destroy($setting->id);
                } catch(Exception $e) {
                    return $this->getErrorResponse('SettingController@index', [$setting->config->app->id, $setting->config->id], Lang::get('api.setting_delete_failed'));
                }
                return $this->getSuccessResponse('SettingController@index', [$setting->config->app->id, $setting->config->id], Lang::get('api.setting_deleted'));
            }
        );
	}

	/**
	 * downloads a file from the storage folder
	 *
	 * @param int $app_id the unique identifier of the application
     * @param int $config_id the unique identifier of the configuration
     * @param int $setting_id the unique identifier of the setting
     * 
	 * @return Response the file as a stream
	 */
	public function download($app_id, $config_id, $setting_id) {
        return Configurely\Setting::getResponse($app_id, $config_id, $setting_id,
            function($setting) {
                $fileinfo = explode(';', $setting->resourceable->value);
                $filepath = str_replace('/','\\',$fileinfo[0]);
                $filename = $fileinfo[1];
                
                $path = storage_path().$filepath;
                
                if(!\File::exists($path)) {
                    return $this->getErrorResponse('SettingController@show', [$setting->config->app->id, $setting->config->_id, $setting->id], Lang::get('api.file_not_found'));;
                }

                return Response::make(File::get($path), 200, array(
                    'Content-Disposition' => 'attachment; filename="'.$filename.'"',
                    'Content-Type' => 'application/octet-stream'
                ));
            }
        );
	}
    
    /**
     * gets a response containing a redirect to the create form with input and validation errors
     * 
     * @return Response an html response
     */
    protected function getRedirectToCreate($config, $validator) {
        return Redirect::action('SettingController@create', [$config->app->id, $config->id])
            ->withInput()
            ->withErrors($validator);
    }

    /**
     * gets a response containing a redirect to the edit form with input and validation errors
     *
     * @return Response an html response
     */
    protected function getRedirectToEdit($setting, $validator) {
        return Redirect::action('SettingController@edit', [$setting->config->app->id, $setting->config->id, $setting->id])
            ->withInput()
            ->withErrors($validator);
    }
    
}
