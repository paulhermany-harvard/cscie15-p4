<?php

class ApiBaseController extends Controller {

    public function __construct() {
        $this->beforeFilter('auth.api');
        $this->beforeFilter('auth');
        $this->beforeFilter('csrf', array('on' => array('post', 'put', 'patch', 'delete')));
    }

    protected function user() {
        return Session::get('apiuser');
    }
    
    protected function setupLayout() {
        if ( ! is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }
    
    public static function getRequestFormat() {
        return Input::get('format', Request::format());
    }
    
    public static function getSuccessResponse($action, $params, $message) {
        if(ApiBaseController::getRequestFormat() == 'html') {
            return Redirect::action($action, $params)
                ->with('flash_message', $message)
                ->with('flash_severity', 'success');
        }
        return Response::json(array('success' => true, 'message' => $message));
    }
    
    public static function getErrorResponse($action, $params, $message) {
        if(ApiBaseController::getRequestFormat() == 'html') {
			return Redirect::action($action, $params)
                ->with('flash_message', $message)
                ->with('flash_severity', 'danger');
        }
        return Response::json(array('success' => false, 'message' => $message));
    }
}
