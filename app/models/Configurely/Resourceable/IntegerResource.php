<?php

namespace Configurely;

class IntegerResource extends Resource {
    protected function rules() {
        return array_merge(
            parent::rules(),
            array (
                'integer_value' => array(
                    'numeric',
                    'required_if:type,integer'
                )
            )
        );
    }
}