<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppconfigsTable extends Migration {

	/**
	 * Creates the appconfigs table, which stores application configurations
	 *
	 * @return void
	**/
	public function up() {
        Schema::create('appconfigs', function($table) {
        
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
            $table->string('name');
            
            /**
             * description
             * stores the description of the configuration
            **/
            $table->text('description');
            
            /**
             * created_at/updated_at
             * stores the timestamp for when the record was created or modified
            **/
            $table->timestamps();
        });
	}

	/**
	 * Drops the appconfigs table
	 *
	 * @return void
	**/
	public function down() {
		Schema::dropIfExists('appconfigs');
	}

}
