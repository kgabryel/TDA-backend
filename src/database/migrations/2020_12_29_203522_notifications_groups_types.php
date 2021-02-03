<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificationsGroupsTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'notifications_groups_types',
            function(Blueprint $table) {
                $table->integer('group_id')
                    ->unsigned();
                $table->integer('type_id')
                    ->unsigned();
                $table->unique(
                    [
                        'group_id',
                        'type_id'
                    ]
                );
                $table->foreign('group_id')
                    ->references('id')
                    ->on('notifications_groups')
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
        Schema::dropIfExists('notifications_groups_types');
    }
}
