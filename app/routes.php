<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function() {
    return View::make('splash');
});

Route::get('generate-auth-token',
    array(
        'before' => 'auth', 
        function() {
            try {
                $token = hash('sha256',Str::random(10),false);

                $user = Auth::user();
                $user->api_token = $token;
                $user->save();

                return Response::json(array('token' => $token, 'user' => $user->toArray()));
            }
            catch(Exception $e) {
                App::abort(404, $e->getMessage());
            }
        }
    )
);

//265667529a7bd748f7c7d3ef0869ba999527c80ac3eab464c3901c64e7837955

Route::group(array('prefix' => 'api', 'before' => 'auth.token'), function() {
    Route::get('/', function() {
        return "Protected resource";
    });
}); 

Route::get('/test', function() {
    $access_token = Request::header('X-Auth-Token');
    return $access_token;
});

Route::group(['prefix' => 'api', 'before' => 'auth.api'], function() {
    Route::group(['prefix' => 'v1'], function() {
        Route::resource('app', 'AppController');
        Route::resource('app.config', 'ConfigController');
        Route::resource('app.config.setting', 'SettingController');
    });
});
   
Route::get('/login',
    array(
        'before' => 'guest',
        function() {
            return View::make('login');
        }
    )
);

Route::post('/login', 
    array(
        'before' => 'csrf', 
        function() {

            $credentials = Input::only('email', 'password');

            if (Auth::attempt($credentials, $remember = true)) {
                return Redirect::intended('/app')
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
    )
);

Route::get('/logout', function() {

    # Log out
    Auth::logout();

    # Send them to the homepage
    return Redirect::to('/');

});

Route::get('/signup',
    array(
        'before' => 'guest',
        function() {
            return View::make('signup');
        }
    )
);

Route::post('/signup', 
    array(
        'before' => 'csrf', 
        function() {

            $user = new User;
            $user->email    = Input::get('email');
            $user->password = Hash::make(Input::get('password'));

            # Try to add the user 
            try {
                $user->save();
            }
            # Fail
            catch (Exception $e) {
                return Redirect::to('/signup')
                    ->with('flash_message', 'Sign up failed; please try again.')
                    ->with('flash_severity', 'danger')
                    ->withInput();
            }

            # Log the user in
            Auth::login($user);

            return Redirect::to('/app')
                ->with('flash_message', 'Welcome to hello!')
                ->with('flash_severity', 'info');

        }
    )
);
