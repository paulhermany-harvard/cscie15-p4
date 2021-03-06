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

        Schema::create('datetime_resources', function($table) {
            /**
             * id
             * stores the unique, sequential identifier of the datetime resource
            **/
            $table->increments('id');
            
            /**
             * value
             * stores the datetime value of the resource
            **/
            $table->datetime('value');
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
             * class_name
             * stores the name of the class for single-table-inheritance
            **/
            $table->string('class_name')->index();
            
            /**
             * value
             * stores the text value of the resource
            **/            
            $table->text('value');
        });
    }

    /**
     * Drops the resourceable tables
     *
     * @return void
    **/
    public function down() {
        Schema::dropIfExists('boolean_resources');
        Schema::dropIfExists('datetime_resources');
        Schema::dropIfExists('integer_resources');
        Schema::dropIfExists('string_resources');
    }

}
