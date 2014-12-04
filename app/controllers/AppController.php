<?php

class AppController extends \ApiBaseController {

	/**
     * Gets a response containing the listing of applications for the current user
	 *
	 * @return an html response if the request format is html, a json response otherwise
	**/
	public function index() {
		$apps = $this->user()->apps()->get();
        
        if($this->getRequestFormat() == 'html') {
            return View::make('app.index')
                ->with('apps', $apps);             
        }
 
        return Response::json($apps);
	}


	/**
	 * Gets a response containing the form for creating a new application
	 *
	 * @return an html response
	**/
	public function create() {
        if($this->getRequestFormat() == 'html') {
            return View::make('app.create');
        }
        // TODO: decide what to do if a json request is made to the create method
	}


	/**
	 * Stores a new application in the database
	 *
	 * @return an html response if the request format is html, a json response otherwise
	**/
	public function store() {
        try {
            $app = new Configurely\App();
            $app->fill(Input::All());
            $app->user()->associate($this->user());
            $app->save();
        } catch(Exception $e) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_creation_failed'));
        }

        return $this->getSuccessResponse('AppController@index', [], Lang::get('api.app_created'));
	}


	/**
	 * Gets a response containing the specified application
     *
	 * @param   int      $app_id        the unique identifier of the application
	 * @return  an html response if the request format is html, a json response otherwise
	**/
	public function show($app_id) {
        try {
			$app = Configurely\App::findOrFail($app_id);
		} catch(Exception $e) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_found'));
		}
        
        if(!$this->user()->owns($app)) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_authorized'));
        }
        
        if($this->getRequestFormat() == 'html') {
            return View::make('app.show')
                ->with('app', $app);
        }
        
        return Response::json($app);
	}


	/**
	 * Gets a response containing the form for editing an existing application
	 *
	 * @param   int      $app_id        the unique identifier of the application
	 * @return an html response if the request format is html, a json response otherwise
	**/
	public function edit($app_id) {
        try {
			$app = Configurely\App::findOrFail($app_id);
		} catch(Exception $e) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_found'));
		}
        
        if(!$this->user()->owns($app)) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_authorized'));
        }
        
        if($this->getRequestFormat() == 'html') {
            return View::make('app.edit')
                ->with('app', $app);
        }
        // TODO: decide what to do if a json request is made to the edit method
	}


	/**
	 * Updates an existing application in the database
	 *
	 * @param   int      $app_id        the unique identifier of the application
	 * @return an html response if the request format is html, a json response otherwise
	 */
	public function update($app_id) {
        try {
			$app = Configurely\App::findOrFail($app_id);
		} catch(Exception $e) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_found'));
		}
        
        if(!$this->user()->owns($app)) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_authorized'));
        }
        
        try {
            $app->fill(Input::All());
            $app->save();
        } catch(Exception $e) {
            return $this->getErrorResponse('AppController@show', [$app_id], Lang::get('api.app_update_failed'));
        }
        
        return $this->getSuccessResponse('AppController@show', [$app_id], Lang::get('api.app_updated'));
	}


	/**
	 * Deletes an existing application from the database
	 *
	 * @param   int      $app_id        the unique identifier of the application
	 * @return an html response if the request format is html, a json response otherwise
	 */
	public function destroy($app_id) {
        try {
			$app = Configurely\App::findOrFail($app_id);
		} catch(Exception $e) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_found'));
		}
        
        if(!$this->user()->owns($app)) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_not_authorized'));
        }
        
        try {
            Configurely\App::destroy($app_id);
        } catch(Exception $e) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_delete_failed'));
        }
		
        return $this->getSuccessResponse('AppController@index', [], Lang::get('api.app_deleted'));
	}
    
}
