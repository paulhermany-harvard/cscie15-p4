<?php
 
class DemoDataSeeder extends Seeder {
 
    public function run() {
        
        $user = User::where('email','=', 'guest@configurely.com')->first();
        if(isset($user)) {
        
            $app = $this->addApp($user->id, 'Sample Application 1');
            $config = $this->addConfig($app->id, 'Development');
            $setting = $this->addSetting($config->id, 'DBName', 'SampleApp1DB');
            $setting = $this->addSetting($config->id, 'DBUsername', 'user-dev');
            $setting = $this->addSetting($config->id, 'DBPassword', 'user-dev-pwd');
            $setting = $this->addSetting($config->id, 'MOTD', 'This is a development environment.');
            $setting = $this->addSetting($config->id, 'APIKey', '6C0765E6-3497-493F-ACF3-2439CB043602');

            $config = $this->addConfig($app->id, 'Production');
            $setting = $this->addSetting($config->id, 'DBName', 'SampleApp1DB');
            $setting = $this->addSetting($config->id, 'DBUsername', 'user-prod');
            $setting = $this->addSetting($config->id, 'DBPassword', 'user-prod-pwd');
            $setting = $this->addSetting($config->id, 'MOTD', 'This is a production environment.');
            $setting = $this->addSetting($config->id, 'APIKey', '7A8A6D2D-AFAC-42D2-883A-6E47A9730CEC');

            $app = $this->addApp($user->id, 'Sample Application 2');
            $config = $this->addConfig($app->id, 'Development');
            $setting = $this->addSetting($config->id, 'DBName', 'SampleApp2DB');
            $setting = $this->addSetting($config->id, 'DBUsername', 'user-dev');
            $setting = $this->addSetting($config->id, 'DBPassword', 'user-dev-pwd');
            $setting = $this->addSetting($config->id, 'ServerName', 'localhost');
            
            $config = $this->addConfig($app->id, 'Production');
            $setting = $this->addSetting($config->id, 'DBName', 'SampleApp2DB');
            $setting = $this->addSetting($config->id, 'DBUsername', 'user-prod');
            $setting = $this->addSetting($config->id, 'DBPassword', 'user-prod-pwd');
            $setting = $this->addSetting($config->id, 'ServerName', 'prod-apps');
            
        }
    }
    
    protected function addApp($user_id, $name) {
        return Configurely\App::firstOrCreate([
            'user_id' => $user_id,
            'name' => $name
        ]);
    }
    
    protected function addConfig($app_id, $name) {
        return Configurely\Config::firstOrCreate([
            'app_id' => $app_id,
            'name' => $name
        ]);
    }
    
    protected function addSetting($config_id, $key, $value) {
        $setting = Configurely\Setting::firstOrCreate(['config_id' => $config_id, 'key' => $key]);
        if(isset($setting->resourceable)) {
            $setting->resourceable->delete();
        }
        $resource = new Configurely\StringResource();
        $resource->value = $value;
        $resource->save();
        $resource->setting()->save($setting);    
        return $setting;
    }
 
}