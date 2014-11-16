<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppsettingsTable extends Migration {

	/**
	 * Creates the appsettings table, which stores application settings
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('appsettings', function($table) {
        
            /**
             * id
             * stores the unique, sequential identifier of the setting
            **/
            $table->increments('id');
            
            /**
             * appconfig_id
             * stores the id of the configuration to which the setting belongs
            **/
            $table->integer('appconfig_id')->unsigned();
            $table->foreign('appconfig_id')->references('id')->on('appconfigs');
            
            /**
             * key
             * stores the key of the setting, which is unique per configuration
            **/
            $table->string('key');
            $table->unique(array('appconfig_id', 'key'));
            
            /**
             * resourceable
             * stores the resourceable_id/resourceable_type of the polymorphic relationship between the setting and the parent resource
            **/
            $table->morphs('resourceable');
        });
	}

	/**
	 * Drops the appsettings table
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('appsettings');
	}

}
