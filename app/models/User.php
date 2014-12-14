<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * provides a list of properties that should be included in the object's array and json output
    */
    protected $visible = array(
        'id',
        'name',
        'email'
    );
    
    /**
     * provides a list of properties to explicitly allow for mass assignment
    */
    protected $fillable = array('email', 'password', 'confirmation_code');
    
    public function apps() {
        return $this->hasMany('Configurely\App');
    }
    
    public function owns($app) {
        return $this->id == $app->user_id;
    }
    
    public function isGuest() {
        return ($this->email === 'guest@configurely.com');
    }
    
    public function getEmailDisplayAttribute() {
        return $this->confirmed ? $this->email : $this->email.' (not verified)';
    }
    
    public static function guest() {
        return User::where('email','=','guest@configurely.com')->first();
    }
}
