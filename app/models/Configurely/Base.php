<?php

namespace Configurely;
use Eloquent;
use Validator;

class Base extends Eloquent {

    public function updated_at_display() {
        $datediff = time() - strtotime($this->updated_at);
        $daysago = floor($datediff/(60*60*24));
        
        if($daysago == 0) {
            $hoursago = floor($datediff/(60*60));
            
            if($hoursago == 0) {
                $minsago = floor($datediff/(60));
                
                if($minsago == 0) {
                    $secsago = floor($datediff);
                    return '' . $secsago . ' second' . ($secsago == 1 ? '' : 's') . ' ago';
                }
                
                return '' . $minsago . ' minute' . ($minsago == 1 ? '' : 's') . ' ago';
            }
            return '' . $hoursago . ' hour' . ($hoursago == 1 ? '' : 's') . ' ago';
        }
        return '' . $daysago . ' day' . ($daysago == 1 ? '' : 's') . ' ago';   
    }

    /**
     * STI Pattern
     * http://www.colorfultyping.com/single-table-inheritance-in-laravel-4/
    **/
    
    public function __construct($attributes = array()) {
        parent::__construct($attributes);
        if ($this->useSti()) {
            $this->setAttribute($this->stiClassField,get_class($this));
        }
    }
    
    private function useSti() {
        return ($this->stiClassField && $this->stiBaseClass);
    }

    public function newQuery($excludeDeleted = true) {
        $builder = parent::newQuery($excludeDeleted);
        if ($this->useSti() && get_class(new $this->stiBaseClass) !== get_class($this)) {
            $builder->where($this->stiClassField,"=",get_class($this));
        }
        return $builder;
    }

    public function newFromBuilder($attributes = array()) {
        if ($this->useSti() && $attributes->{$this->stiClassField}) {
            $class = $attributes->{$this->stiClassField};
            $instance = new $class;
            $instance->exists = true;
            $instance->setRawAttributes((array) $attributes, true);
            return $instance;
        }
        return parent::newFromBuilder($attributes);
    }

    /**
     * Validation Pattern
     * http://daylerees.com/trick-validation-within-models
    **/
    
    protected $rules = array();

    protected $errors;
    
    public function validate($data) {
        $validator = Validator::make($data, $this->rules);
        if ($validator->fails()) {
            $this->errors = $validator->errors;
            return false;
        }
        return true;
    }

    public function errors() {
        return $this->errors;
    }
    
}