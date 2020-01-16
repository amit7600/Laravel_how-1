<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('address_recordid')->nullable();
            $table->string('address')->nullable();
            $table->string('address_1')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_state')->nullable();
            $table->string('address_zip_code')->nullable();
            $table->string('address_id')->nullable();
            $table->string('address_region')->nullable();
            $table->string('address_country')->nullable();
            $table->string('address_attention')->nullable();
            $table->string('address_type')->nullable();
            $table->string('address_locations')->nullable();
            $table->string('address_contact')->nullable();
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
        Schema::dropIfExists('address');
    }
}
