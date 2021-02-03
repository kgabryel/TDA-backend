<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificationsBuffCreated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'notifications_buff',
            function(Blueprint $table) {
                $table->id();
                $table->string('title', 100);
                $table->text('content')
                    ->nullable();
                $table->dateTime('time');
                $table->boolean('locked')
                    ->default(false);
                $table->unsignedBigInteger('notification_id');
                $table->unsignedBigInteger('user_id');
                $table->foreign('notification_id')
                    ->references('id')
                    ->on('notifications')
                    ->onDelete('cascade');
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('notifications_buff');
    }
}
