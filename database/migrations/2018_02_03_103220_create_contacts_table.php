<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('contact_recordid');
            $table->integer('contact_id')->nullable();
            $table->string('contact_first_name')->nullable();
            $table->string('contact_middle_name')->nullable();
            $table->string('contact_last_name')->nullable();
            $table->string('contact_organizations')->nullable();
            $table->string('contact_organization_id')->nullable();
            $table->string('contact_type')->nullable();
            $table->string('contact_languages_spoken')->nullable();
            $table->string('contact_other_languages')->nullable();
            $table->string('contact_religious_title')->nullable();
            $table->string('contact_title')->nullable();
            $table->string('contact_pronouns')->nullable();
            $table->string('contact_mailing_address')->nullable();
            $table->string('contact_cell_phones')->nullable();
            $table->string('contact_office_phones')->nullable();
            $table->string('contact_emergency_phones')->nullable();
            $table->string('contact_office_fax_phones')->nullable();
            $table->string('contact_personal_email')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('flag')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contacts');
    }
}
