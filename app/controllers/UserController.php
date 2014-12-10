<?php

class UserController extends Controller {

    /**
     * creates a new UserController instance
     */
    public function __construct() {
        $this->beforeFilter('auth', array('only' => ['getLogout']));
        //$this->beforeFilter('guest', array('except' => ['getLogout']));
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
            'email' => ['email', 'max:255', 'required', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:6']
        ]);
        
        if ($validator->fails()) {
            return Redirect::action('UserController@getSignup')
                ->withInput()
                ->withErrors($validator);
        }
    
        // get the authenticator, which will be used to salt the password hash
        $salt = $_ENV['authenticator'];
        
        // generate the confirmation code, which will be used for email verification
        $confirmation_code = Hash::make(Input::get('email') . $salt);        
    
        try {
            $user = User::create([
                'email' => Input::get('email'),
                'password' => Hash::make(Input::get('password') . $salt),
                'confirmation_code' => $confirmation_code
            ]);
            
            $data = ['user' => $user];
            
            Mail::send('emails.verify', $data, function($message) {
                $message->to(
                    Input::get('email'),
                    Input::get('email')
                )->subject(Lang::get('app.verify_subject'));
            });

            return Redirect::to('/')
                ->with('flash_message', Lang::get('app.signup_pending'))
                ->with('flash_severity', 'warning');
        } catch (Exception $e) {
            return Redirect::to('/signup')
                ->with('flash_message', Lang::get('app.signup_failed'))
                ->with('flash_severity', 'danger')
                ->withInput();
        }
    }
    
    /**
     * ends the current session and redirects the user to the homepage
     */
    public function getLogout() {
        Auth::logout();
        Session::flush();
        return Redirect::to('/')
            ->with('flash_message', 'You are now logged out.')
            ->with('flash_severity', 'info');
    }
    
}
