<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('location_recordid')->nullable();
            $table->string('location_name')->nullable();
            $table->string('location_organization')->nullable();
            $table->string('location_id')->nullable();
            $table->string('location_type')->nullable();
            $table->string('location_address')->nullable();
            $table->string('location_congregation')->nullable();
            $table->string('location_building_status')->nullable();
            $table->string('location_call')->nullable();
            $table->string('location_description')->nullable();
            $table->string('location_services')->nullable();
            $table->string('location_contact')->nullable();
            $table->string('location_details')->nullable();
            $table->string('flag', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
