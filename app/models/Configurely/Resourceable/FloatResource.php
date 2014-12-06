<?php

namespace Configurely;

class FloatResource extends Resource {
    protected function rules() {
        return array_merge(
            parent::rules(),
            array (
                'float_value' => array(
                    'numeric',
                    'required_if:type,float'
                )
            )
        );
    }
}