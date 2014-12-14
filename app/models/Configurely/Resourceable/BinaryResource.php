<?php

namespace Configurely;

class BinaryResource extends StringResource {
    
    protected function rules() {
        return array_merge(
            parent::rules(),
            array (
                'binary_value' => array(
                    'max:65',
                    'required_if:type,binary'
                )
            )
        );
    }

    public function delete() {
        
        $fileinfo = explode(';', $this->value);
        $filepath = str_replace('/','\\',$fileinfo[0]);
        
        $path = storage_path().$filepath;
        
        if(\File::exists($path)) {
            \File::delete($path);
        }
        
        return parent::delete();
    }

    public function getStringValue() {
        return \URL::action('SettingController@download', [$this->setting->config->app->id, $this->setting->config->id, $this->setting->id]);
    }
    
    public function render() {
        return \View::make('resourceable.binary')
            ->with('resource', $this);
    }
    
    public function setValue($setting) {
        try {
            $this->value = BinaryResource::saveFile(
                \Input::file('binary_value'), 
                $setting->key, 
                \Auth::user()->id,
                $setting->config->app->id,
                $setting->config->id
            );
        } catch(Exception $e) {
            return false;
        }
        return true;
    }
    
    public static function getFileName($key) {
        return preg_replace('/[^a-zA-Z0-9-_\.]/','-', $key);
    }
    
    public static function getFilePath($user_id, $app_id, $config_id) {
        return '/uploads/'.$user_id.'/'.$app_id.'/'.$config_id;
    }
    
    public static function saveFile($file, $key, $user_id, $app_id, $config_id) {
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension(); 
        
        $destPath = BinaryResource::getFilePath($user_id, $app_id, $config_id).'\\';
        $destFilename = BinaryResource::getFileName($key).'.'.$extension;
        
        // move the file to the storage directory
        $file->move(storage_path().$destPath, $destFilename);
        
        return $destPath.$destFilename.';'.$filename;
    }
}