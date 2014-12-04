<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceableTables extends Migration {

    /**
     * Creates the resourceable "parent" tables for the application setting value
     *
     * @return void
    **/
    public function up() {

        Schema::create('boolean_resources', function($table) {
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

        Schema::create('float_resources', function($table) {
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
        
        Schema::create('integer_resources', function($table) {
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

        Schema::create('string_resources', function($table) {
            
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
    **/
    public function down() {
        Schema::dropIfExists('boolean_resource');
        Schema::dropIfExists('float_resource');
        Schema::dropIfExists('integer_resource');
        Schema::dropIfExists('string_resource');
    }

}
