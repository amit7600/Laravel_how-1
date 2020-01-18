<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('campaign_type')->nullable();
            $table->string('subject')->nullable();
            $table->string('campaign_file')->nullable();
            $table->string('group_id')->nullable();
            $table->text('body')->nullable();
            $table->bigInteger('status')->nullable();
            $table->string('recipient')->nullable();
            $table->bigInteger('sending_type')->nullable();
            $table->date('schedule_date')->nullable();
            $table->string('sending_status')->nullable();
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
        Schema::dropIfExists('campaigns');
    }
}
