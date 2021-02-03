<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificationsGroupsCreated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'notifications_groups',
            function(Blueprint $table) {
                $table->id();
                $table->bigInteger('time');
                $table->uuid('alarm_id');
                $table->foreign('alarm_id')
                    ->references('id')
                    ->on('alarms_groups')
                    ->onDelete('cascade');
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications_groups');
    }
}
