<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppsTable extends Migration {

    /**
     * Create the apps table, which stores applications
     *
     * @return void
    **/
    public function up() {
        Schema::create('apps', function($table) {
        
            /**
             * id
             * stores the unique, sequential identifier of the application
            **/
            $table->increments('id');
            
            /**
             * user_id
             * stores the user id of the owner of the application
            **/
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            
            /**
             * name
             * stores the full name of the application
            **/
            $table->string('name');
            
            /**
             * description
             * stores a description of the application
            **/
            $table->text('description');
            
            /**
             * live_url
             * stores the live url of the application
            **/
            $table->string('live_url');
            
            /**
             * scm_url
             * stores the source code management (scm) url of the application
            **/
            $table->string('scm_url');
            
            /**
             * created_at/updated_at
             * stores the timestamp for when the record was created or modified
            **/
            $table->timestamps();
        });
    }

    /**
     * Drops the apps table
     *
     * @return void
    **/
    public function down() {
        Schema::dropIfExists('apps');
    }

}
