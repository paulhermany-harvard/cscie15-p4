<?php

namespace Configurely;

class DatetimeResource extends Resource {
    protected function rules() {
        return array_merge(
            parent::rules(),
            array (
                'datetime_value' => array(
                    'date',
                    'required_if:type,datetime'
                )
            )
        );
    }
    
    public function setValue($setting) {
        try {
            $this->value = \Input::get('datetime_value');
        } catch(Exception $e) {
            return false;
        }
        return true;
    }
}