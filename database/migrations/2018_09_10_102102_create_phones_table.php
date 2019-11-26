<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('phone_recordid')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('phone_extension')->nullable();
            $table->string('phone_type')->nullable();
            $table->string('phone_office')->nullable();
            $table->string('phone_office_fax')->nullable();
            $table->string('phone_emergency')->nullable();
            $table->string('phone_contacts')->nullable();
            $table->string('phone_language')->nullable();
            $table->string('phone_description')->nullable();
            $table->string('phone_id')->nullable();
            $table->string('phone_locations')->nullable();
            $table->string('phone_services')->nullable();
            $table->string('phone_organizations')->nullable();
            $table->string('phone_details')->nullable();
            $table->string('phone_schedule')->nullable();
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
        Schema::dropIfExists('phones');
    }
}
