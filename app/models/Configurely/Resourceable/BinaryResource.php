<?php

namespace Configurely;

class BinaryResource extends StringResource {
    protected $rules = array(
        'binary_value' => 'max:10'
    );
}