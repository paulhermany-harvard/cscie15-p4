<?php
 
class DemoDataSeeder extends Seeder {
 
    public function run() {
        
        $user = User::where('email','=', 'guest@configurely.com')->first();
        if(isset($user)) {
        
            $app = $this->addApp($user->id, 'Configurely');
            $config = $this->addConfig($app->id, 'Development');
            $setting = $this->addSetting($config->id, 'API App Url', 'http://configurely.local.com/api/v1/app');
            
            $config = $this->addConfig($app->id, 'Production');
            $setting = $this->addSetting($config->id, 'API App Url', 'http://configurely.com/api/v1/app');
            
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