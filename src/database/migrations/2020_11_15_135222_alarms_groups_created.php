<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlarmsGroupsCreated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'alarms_groups',
            function(Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('title', 100);
                $table->date('start');
                $table->date('stop')
                    ->nullable();
                $table->unsignedInteger('interval')
                    ->nullable();
                $table->uuid('task_id')
                    ->nullable();
                $table->enum(
                    'interval_type',
                    [
                        'day',
                        'week',
                        'month'
                    ]
                )
                    ->nullable();
                $table->text('content')
                    ->nullable();
                $table->boolean('active')
                    ->default(true);
                $table->unsignedBigInteger('user_id');
                $table->foreign('task_id')
                    ->references('id')
                    ->on('tasks_groups')
                    ->onDelete('cascade');
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
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
        Schema::dropIfExists('alarms_groups');
    }
}
