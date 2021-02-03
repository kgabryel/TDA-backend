<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificationsTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'notifications_types',
            function(Blueprint $table) {
                $table->integer('notification_id')
                    ->unsigned();
                $table->integer('type_id')
                    ->unsigned();
                $table->unique(
                    [
                        'notification_id',
                        'type_id'
                    ]
                );
                $table->foreign('notification_id')
                    ->references('id')
                    ->on('notifications')
                    ->onDelete('cascade');
                $table->foreign('type_id')
                    ->references('id')
                    ->on('available_notifications_types')
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
        Schema::dropIfExists('notifications_types');
    }
}
