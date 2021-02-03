<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificationsCreated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'notifications',
            function(Blueprint $table) {
                $table->id();
                $table->dateTime('time');
                $table->boolean('checked');
                $table->uuid('alarm_id');
                $table->unsignedBigInteger('group_id')
                    ->nullable();
                $table->foreign('alarm_id')
                    ->references('id')
                    ->on('alarms')
                    ->onDelete('cascade');
                $table->foreign('group_id')
                    ->references('id')
                    ->on('notifications_groups')
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
        Schema::dropIfExists('notifications');
    }
}
