<?php

class UserController extends Controller {

    /**
     * creates a new UserController instance
     */
    public function __construct() {
        $this->beforeFilter('auth', array('only' => ['getLogout', 'getProfile']));
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
            if(Auth::user()->confirmed) {        
                return Redirect::intended('/')
                    ->with('flash_message', 'Welcome Back!')
                    ->with('flash_severity', 'info');
            } else {
                return Redirect::intended('/')
                    ->with('flash_message', Lang::get('app.verify_pending'))
                    ->with('flash_severity', 'warning');
            }
        }
        else {
            return Redirect::to('/login')
                ->with('flash_message', 'Log in failed; please try again.')
                ->with('flash_severity', 'danger');
        }

        return Redirect::to('login');
    }
    
    /**
     * gets a response containing the user profile form
     */
    public function getProfile() {
        return View::make('profile')
            ->with('user', Auth::user());
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
    
        // generate the confirmation code, which will be used for email verification
        $confirmation_code = base64_encode(Hash::make(Input::get('email') . str_random(32)));
    
        try {
            $user = User::create([
                'email' => Input::get('email'),
                'password' => Hash::make(Input::get('password')),
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
     * confirms the confirmation code, completes the registration process, and logs in the user
     */    
    public function getVerify($confirmation_code = null) {

        if(is_null($confirmation_code)) {
            if(Auth::check()) {
                $user = Auth::user();
                
                if($user->confirmed) {
                    return Redirect::to('/')
                        ->with('flash_message', Lang::get('app.verify_success'))
                        ->with('flash_severity', 'success');
                } else {
                    return View::make('verify')
                        ->with('user', Auth::user());
                }
                
            } else {
                return Redirect::guest('login');
            }
        }
        
        try {
            $user = User::whereConfirmationCode($confirmation_code)->firstOrFail();
        } catch(Exception $e) {
            return Redirect::to('/')
                ->with('flash_message', Lang::get('app.verify_failed'))
                ->with('flash_severity', 'danger');            
        }

        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();

        Auth::login($user);
        
        return Redirect::to('/')
            ->with('flash_message', Lang::get('app.verify_success'))
            ->with('flash_severity', 'success');
    }
    
    /**
     * generates a new verification code and resends the email
     */
    public function postVerify() {
        
        $user = Auth::user();
        
        $confirmation_code = base64_encode(Hash::make($user->email . str_random(32)));
        
        $user->confirmation_code = $confirmation_code;
        $user->save();
        
        $data = ['user' => $user];
        
        Mail::send('emails.verify', $data, function($message) {
            $user = Auth::user();
            $message->to(
                $user->email,
                $user->email
            )->subject(Lang::get('app.verify_subject'));
        });
        
        return Redirect::to('/')
            ->with('flash_message', Lang::get('app.verify_pending'))
            ->with('flash_severity', 'warning');
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
