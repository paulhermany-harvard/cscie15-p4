<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration {

    /**
     * Creates the settings table, which stores application settings
     *
     * @return void
     */
    public function up() {
        Schema::create('settings', function($table) {
        
            /**
             * id
             * stores the unique, sequential identifier of the setting
            **/
            $table->increments('id');
            
            /**
             * appconfig_id
             * stores the id of the configuration to which the setting belongs
            **/
            $table->integer('config_id')->unsigned();
            $table->foreign('config_id')->references('id')->on('configs');
            
            /**
             * key
             * stores the key of the setting, which is unique per configuration
            **/
            $table->string('key');
            $table->unique(array('config_id', 'key'));
            
            /**
             * resourceable
             * stores the resourceable_id/resourceable_type of the polymorphic relationship between the setting and the parent resource
            **/
            $table->morphs('resourceable');
            
            /**
             * created_at/updated_at
             * stores the timestamp for when the record was created or modified
            **/
            $table->timestamps();
        });
    }

    /**
     * Drops the settings table
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('settings');
    }

}
