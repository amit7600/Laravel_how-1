<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('organization_recordid')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('organization_id')->nullable();
            $table->string('organization_alt_id')->nullable();
            $table->string('organization_religion', 500)->nullable();
            $table->string('organization_faith_tradition', 500)->nullable();
            $table->string('organization_denomination', 500)->nullable();
            $table->string('organization_judicatory_body', 500)->nullable();
            $table->string('organization_type')->nullable();
            $table->string('organization_url')->nullable();
            $table->string('organization_facebook')->nullable();
            $table->string('organization_c_board')->nullable();
            $table->string('organization_internet_access')->nullable();
            $table->text('organization_description')->nullable();
            $table->string('organization_locations')->nullable();
            $table->string('organization_borough')->nullable();
            $table->string('organization_zipcode')->nullable();
            $table->string('organization_details')->nullable();
            $table->string('organization_contact')->nullable();
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
        Schema::dropIfExists('organizations');
    }
}
