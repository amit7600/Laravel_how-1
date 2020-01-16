<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('service_recordid')->nullable();
            $table->string('service_name')->nullable();
            $table->string('service_organization')->nullable();
            $table->string('service_id')->nullable();
            $table->string('service_alternate_name')->nullable();
            $table->string('service_description')->nullable();
            $table->string('service_locations')->nullable();
            $table->string('service_address')->nullable();
            $table->string('service_url')->nullable();
            $table->string('service_email')->nullable();
            $table->string('service_status')->nullable();
            $table->string('service_interpretation')->nullable();
            $table->string('service_application_process')->nullable();
            $table->string('service_taxonomy')->nullable();
            $table->string('service_phones')->nullable();
            $table->string('service_schedule')->nullable();
            $table->string('service_contacts')->nullable();
            $table->string('service_details')->nullable();
            $table->string('service_area')->nullable();
            $table->string('service_metadata')->nullable();
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
        Schema::dropIfExists('services');
    }
}
