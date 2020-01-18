<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('contact_id')->nullable();
            $table->enum('type', [1, 2, 3, 4])->nullable()->comment('1 = Email and 2 = SMS and 3 = Audio and 4 = Audio + SMS');
            $table->bigInteger('campaign_id')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('date_sent')->nullable();
            $table->string('direction')->nullable();
            $table->string('toNumber')->nullable();
            $table->string('toContact')->nullable();
            $table->string('fromNumber')->nullable();
            $table->string('fromContact')->nullable();
            $table->text('subject')->nullable();
            $table->text('body')->nullable();
            $table->text('mediaurl')->nullable();
            $table->string('error_message')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_reports');
    }
}
