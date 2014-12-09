<?php

class UserController extends Controller {

    /**
     * creates a new UserController instance
     */
    public function __construct() {
        $this->beforeFilter('auth', array('only' => ['getLogout']));
        $this->beforeFilter('guest', array('except' => ['getLogout']));
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    /**
     * gets a response containing the login form
     */
    public function getLogin() {
        return View::make('login');
    }
    
    /**
     * attempts a login using the supplied credentials and redirects the user to the requested page
     */
    public function postLogin() {
        $credentials = Input::only('email', 'password');

        if (Auth::attempt($credentials, $remember = true)) {
            return Redirect::intended('/')
                ->with('flash_message', 'Welcome Back!')
                ->with('flash_severity', 'info');
        }
        else {
            return Redirect::to('/login')
                ->with('flash_message', 'Log in failed; please try again.')
                ->with('flash_severity', 'danger');
        }

        return Redirect::to('login');
    }
    
    /**
     * gets a response containing the signup form
     */
    public function getSignup() {
        return View::make('signup');
    }

    /**
     * creates and logs in the user
     */
    public function postSignup() {
    
        $validator = Validator::make(Input::all(), [
            'email' => ['email', 'max:255', 'required'],
            'password' => 'required'
        ]);
        
        if ($validator->fails()) {
            return Redirect::action('UserController@getSignup')
                ->withInput()
                ->withErrors($validator);
        }
    
        $user = new User;
        $user->email = Input::get('email');
        $user->password = Hash::make(Input::get('password'));

        try {
            $user->save();
        } catch (Exception $e) {
            return Redirect::to('/signup')
                ->with('flash_message', 'Sign up failed; please try again.')
                ->with('flash_severity', 'danger')
                ->withInput();
        }

        Auth::login($user);

        return Redirect::to('/')
            ->with('flash_message', 'Welcome to Configurely!')
            ->with('flash_severity', 'info');
    }
    
    /**
     * ends the current session and redirects the user to the homepage
     */
    public function getLogout() {
        Auth::logout();
        return Redirect::to('/')
            ->with('flash_message', 'You are now logged out.')
            ->with('flash_severity', 'info');
    }
    
}
