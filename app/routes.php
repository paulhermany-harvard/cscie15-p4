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

Route::get('/', array(
    'before' => 'guest',
    function() {
        return View::make('splash');
    }
));

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

Route::get('/test', function() {
    $access_token = Request::header('X-Auth-Token');
    return $access_token;
});

Route::get('/d3data', array(
    'before' => 'guest',
    function() {
        $data = new D3\Node(null, 'My Applications', 'root');
        
        $apps = Auth::user()->apps()->get();
        foreach($apps as $app) {
            $app_node = $data->addChild($app->id, $app->name, 'app');
            
            $configs = $app->configs;
            foreach($configs as $config) {
            
                $config_node =  $app_node->addChild($config->id, $config->name, 'config');
                
                $settings = $config->settings;
                foreach($settings as $setting) {
                    
                    $settings_node = $config_node->addChild($setting->id, $setting->key, 'setting');
                    
                    $settings_node->addChild(null, $setting->value, 'resource');
                    
    //                $leaf = new D3\Node($setting->key);
                    
      //              array_push($config_node->children, $leaf);
                    
                    //$setting_node = $config_node->addChild();
                    //$setting_node->addChild($setting->value);
                }
            }        
        }
        
        return Response::json($data);
    }
));

Route::group(['prefix' => 'api', 'before' => 'auth.api'], function() {
    Route::group(['prefix' => 'v1'], function() {
        Route::resource('app', 'AppController');
        Route::resource('app.config', 'ConfigController');
        Route::resource('app.config.setting', 'SettingController');
        Route::get('/app/{app_id}/config/{config_id}/setting/{setting_id}/download', 'SettingController@download');
    });
});

Route::get('/login', 'UserController@getLogin');
Route::post('/login', 'UserController@postLogin');

Route::get('/signup', 'UserController@getSignup');
Route::post('/signup', 'UserController@postSignup');

Route::get('/logout', 'UserController@getLogout');
