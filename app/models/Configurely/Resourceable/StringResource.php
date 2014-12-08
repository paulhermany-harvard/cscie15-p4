<?php

namespace Configurely;

class StringResource extends Resource {

    protected $table = 'string_resources';

    protected $stiClassField = 'class_name';
    protected $stiBaseClass = 'Configurely\StringResource';

    protected function rules() {
        return array_merge(
            parent::rules(),
            array (
                'string_value' => array(
                    'max:65',
                    'required_if:type,string'
                )
            )
        );
    }
 
    public function setValue($setting) {
        try {
            $this->value = \Input::get('string_value');
        } catch(Exception $e) {
            return false;
        }
        return true;
    }
}