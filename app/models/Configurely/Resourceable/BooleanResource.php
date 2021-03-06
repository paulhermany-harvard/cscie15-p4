<?php

namespace Configurely;

class BooleanResource extends Resource {    
    protected function rules() {
        return array_merge(
            parent::rules(),
            array (
                'boolean_value' => array(
                    'required_if:type,binary'
                )
            )
        );
    }
    
    public function setValue($setting) {
        try {
            $this->value = (\Input::get('boolean_value') === 'on');
        } catch(Exception $e) {
            return false;
        }
        return true;
    }
}