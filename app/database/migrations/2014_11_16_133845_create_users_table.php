<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Creates the users table, which will contain the owners of the applications
	 *
	 * @return void
	**/
	public function up() {
        Schema::create('users', function($table) {
        
            /**
             * id
             * stores the unique, sequential identifier of the user
            **/
            $table->increments('id');
            
            /**
             * email
             * stores the email address of the user, which is a unique, non-sequential identifier for the user
            **/
            $table->string('email')->unique();
            
            /**
             * password
             * stores the hashed password of the user
            **/
            $table->string('password');
            
            /**
             * remember_token
             * stores the token for "remember me" sessions
            **/
            $table->rememberToken();
            
            /**
             * created_at/updated_at
             * stores the timestamp for when the record was created or modified
            **/
            $table->timestamps();
        });
	}

	/**
	 * Drops the users table
	 *
	 * @return void
	**/
	public function down() {
        Schema::dropIfExists('users');
	}

}