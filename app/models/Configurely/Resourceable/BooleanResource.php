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
}