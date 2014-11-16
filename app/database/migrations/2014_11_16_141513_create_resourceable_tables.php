<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceableTables extends Migration {

	/**
	 * Creates the resourceable "parent" tables for the application setting value
	 *
	 * @return void
	 */
	public function up() {
    
        Schema::create('binaryresource', function($table) {
        
            /**
             * id
             * stores the unique, sequential identifier of the binary resource
            **/
            $table->increments('id');
            
            /**
             * value
             * stores the binary value of the resource
            **/
            $table->binary('value');
        });

        Schema::create('booleanresource', function($table) {
            /**
             * id
             * stores the unique, sequential identifier of the boolean resource
            **/
            $table->increments('id');
            
            /**
             * value
             * stores the boolean value of the resource
            **/
            $table->boolean('value');
        });

        Schema::create('floatresource', function($table) {
            /**
             * id
             * stores the unique, sequential identifier of the floating point resource
            **/
            $table->increments('id');
            
            /**
             * value
             * stores the floating point value of the resource
            **/
            $table->float('value');
        });
        
        Schema::create('integerresource', function($table) {
            /**
             * id
             * stores the unique, sequential identifier of the integer resource
            **/
            $table->increments('id');
            
            /**
             * value
             * stores the integer value of the resource
            **/
            $table->integer('value');
        });

        Schema::create('stringresource', function($table) {
            
            /**
             * id
             * stores the unique, sequential identifier of the string resource
            **/
            $table->increments('id');
            
            /**
             * value
             * stores the string value of the resource
            **/            
            $table->string('value');
        });
	}

	/**
	 * Drops the resourceable tables
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('binaryresource');
        Schema::dropIfExists('booleanresource');
        Schema::dropIfExists('floatresource');
        Schema::dropIfExists('integerresource');
        Schema::dropIfExists('stringresource');
	}

}
