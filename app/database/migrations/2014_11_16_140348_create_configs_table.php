<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsTable extends Migration {

    /**
     * Creates the configs table, which stores application configurations
     *
     * @return void
    **/
    public function up() {
        Schema::create('configs', function($table) {
        
            /**
             * id
             * stores the unique, sequential identifier of the configuration
            **/
            $table->increments('id');
            
            /**
             * app_id
             * stores the id of the application to which the configuration belongs
            **/
            $table->integer('app_id')->unsigned();
            $table->foreign('app_id')->references('id')->on('apps');
            
            /**
             * name
             * stores the name of the configuration
            **/
            $table->string('name', 255);
            
            /**
             * description
             * stores the description of the configuration
            **/
            $table->text('description', 4000);
            
            /**
             * created_at/updated_at
             * stores the timestamp for when the record was created or modified
            **/
            $table->timestamps();
        });
    }

    /**
     * Drops the configs table
     *
     * @return void
    **/
    public function down() {
        Schema::dropIfExists('configs');
    }

}
