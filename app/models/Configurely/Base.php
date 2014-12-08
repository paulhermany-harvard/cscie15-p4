<?php

namespace Configurely;

/**
 * This class provides a base model for Configurely applications, configurations, settings, and resources.
 * 
 * This class implements the following patterns:
 * 1. STI Pattern
 *    http://www.colorfultyping.com/single-table-inheritance-in-laravel-4/
 *    The purpose of this pattern is to provide all models with single-table-inheritance so multiple models of the same base data type can be stored in a single table.
 * 2. Validation Pattern
 *    http://daylerees.com/trick-validation-within-models
 *    The purpose of this pattern is to provide model-specific validation rules.
*/
class Base extends \Eloquent {

    /**
     * stores the validator instance for the model
    */
    protected $validator;

    /**
     * gets the updated_at property displayed as a friendly "days ago" string with proper pluralization
     *
     * @return string a string representation of the updated_at timestamp
    */
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
     * overrides the Eloquent constructor to initialize the STI attributes
    */
    public function __construct($attributes = array()) {
        parent::__construct($attributes);
        if ($this->useSti()) {
            $this->setAttribute($this->stiClassField,get_class($this));
        }
    }
    
    /**
     * overrides the Eloquent method to create a new query, optionally using STI
     *
     * return mixed a query builder object
    */
    public function newQuery($excludeDeleted = true) {
        $builder = parent::newQuery($excludeDeleted);
        if ($this->useSti() && get_class(new $this->stiBaseClass) !== get_class($this)) {
            $builder->where($this->stiClassField,"=",get_class($this));
        }
        return $builder;
    }

    /**
     * overrides the Eloquent method to create a new instance, optionally using STI
     *
     * @return mixed an instance of the class
    */
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
     * helper method to determine whether to use single table inheritance
     *
     * @return boolean true if the model uses single table inheritance, false otherwise
    */
    private function useSti() {
        return ($this->stiClassField && $this->stiBaseClass);
    }

    /**
     * gets the array of validation rules for the model
     * 
     * return array an array of validation rules
    */
    protected function rules() {
        return array();
    }

    /**
     * gets the validator instance for the model
     *
     * @return \Validator a validator instance
    */
    public function validator() {
        return $this->validator;
    }
    
    /**
     * creates and sets the validator instance, and validates the data using the rules for the model
     *
     * @return boolean true if the data is valid, false otherwise
    */
    public function validate($data) {
        $this->validator = \Validator::make($data, $this->rules());
        if ($this->validator->fails()) {
            return false;
        }        
        return true;
    }
    
}