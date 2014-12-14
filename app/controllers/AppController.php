<?php

class AppController extends \ApiBaseController {

	/**
     * gets a response containing the listing of applications for the current user or an error response indicating that the applications can not be listed as requested
	 *
	 * @return Response an html response if the request format is html, a json response otherwise
	 */
	public function index() {
		$apps = Auth::user()->apps()->get();
        
        if($this->getRequestFormat() == 'html') {
            return View::make('app.index')
                ->with('apps', $apps);             
        }
        return Response::json($apps);
	}


	/**
	 * gets a response containing the form for creating a new application for the current user or an error response indicating that the application can not be created
	 *
	 * @return Response an html response
	 */
	public function create() {
        if($this->getRequestFormat() == 'html') {
            return View::make('app.create');
        }
        // TODO: decide what to do if a json request is made to the create method
	}


	/**
	 * gets a success response indicating that the application is stored successfully or an error response indicating that the application can not be stored
	 *
	 * @return Response an html response if the request format is html, a json response otherwise
	 */
	public function store() {
        try {
            $app = new Configurely\App();
            if(!$app->validate(Input::all())) {
                return $this->getRedirectToCreate($app->validator());
            }
            
            $app->fill(Input::All());
            $app->user()->associate($this->user());
            $app->save();
        } catch(Exception $e) {
            return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_creation_failed'));
        }
        return $this->getSuccessResponse('AppController@index', [], Lang::get('api.app_created'));
	}


	/**
	 * calls the Configurely\App::getResponse method to get a response containing a view of the specified application or an error response indicating that the application can not be displayed
     *
	 * @param int $app_id the unique identifier of the application
     * 
	 * @return Response an html response if the request format is html, a json response otherwise
	 */
	public function show($app_id) {
        return Configurely\App::getResponse($app_id,
            function($app) {
                if($this->getRequestFormat() == 'html') {
                    return View::make('app.show')
                        ->with('app', $app);
                }
                return Response::json($app);
            }
        );
	}


	/**
	 * calls the Configurely\App::getResponse method to get a response containing the form for editing the specified application or an error response indicating that the application can not be edited
	 *
	 * @param int $app_id the unique identifier of the application
     
	 * @return Response an html response if the request format is html, a json response otherwise
	 */
	public function edit($app_id) {
        return Configurely\App::getResponse($app_id,
            function($app) {
                if($this->getRequestFormat() == 'html') {
                    return View::make('app.edit')
                        ->with('app', $app);
                }
                // TODO: decide what to do if a json request is made to the edit method
            }
        );
	}


	/**
	 * calls the Configurely\App::getResponse method to get a success response indicating that the specified application is updated or an error response indicating that the application can not be updated
	 *
	 * @param int $app_id the unique identifier of the application
     
	 * @return Response an html response if the request format is html, a json response otherwise
	 */
	public function update($app_id) {
        return Configurely\App::getResponse($app_id,
            function($app) {
                try {
                    if(!$app->validate(Input::all())) {
                        return $this->getRedirectToEdit($app, $app->validator());
                    }
                    
                    $app->fill(Input::All());
                    $app->touch();
                    $app->save();
                } catch(Exception $e) {
                    return $this->getErrorResponse('AppController@show', [$app->id], Lang::get('api.app_update_failed'));
                }
                return $this->getSuccessResponse('AppController@show', [$app->id], Lang::get('api.app_updated'));
            }
        );
	}


	/**
	 * calls the Configurely\App::getResponse method to get a success response indicating that the specified application is deleted or an error response indicating that the application can not be deleted
	 *
	 * @param int $app_id the unique identifier of the application
	 * @return Response an html response if the request format is html, a json response otherwise
	 */
	public function destroy($app_id) {
        return Configurely\App::getResponse($app_id,
            function($app) {
                try {
                    Configurely\App::destroy($app->id);
                } catch(Exception $e) {
                    return $this->getErrorResponse('AppController@index', [], Lang::get('api.app_delete_failed'));
                }
                return $this->getSuccessResponse('AppController@index', [], Lang::get('api.app_deleted'));
            }
        );
	}
 
    /**
     * gets a response containing a redirect to the create form with input and validation errors
     * 
     * @return Response an html response
     */
    protected function getRedirectToCreate($validator) {
        return Redirect::action('AppController@create')
            ->withInput()
            ->withErrors($validator);
    }

    /**
     * gets a response containing a redirect to the edit form with input and validation errors
     *
     * @return Response an html response
     */
    protected function getRedirectToEdit($app, $validator) {
        return Redirect::action('AppController@edit', [$app->id])
            ->withInput()
            ->withErrors($validator);
    }
}
