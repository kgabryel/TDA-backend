<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TasksGroupsCreated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'tasks_groups',
            function(Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('title', 100);
                $table->date('start');
                $table->date('stop')
                    ->nullable();
                $table->text('content')
                    ->nullable();
                $table->unsignedInteger('interval')
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
                $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('tasks_groups');
    }
}
