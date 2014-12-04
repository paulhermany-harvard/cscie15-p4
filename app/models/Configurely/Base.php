<?php

namespace Configurely;
use Eloquent;

class Base extends Eloquent {

    protected $rules = array();

    protected $errors;

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