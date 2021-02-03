<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TasksCreated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'tasks',
            function(Blueprint $table) {
                $table->uuid('id')
                    ->primary();
                $table->string('title', 100);
                $table->text('content')
                    ->nullable();
                $table->date('date')
                    ->nullable();
                $table->uuid('parent_id')
                    ->nullable();
                $table->unsignedBigInteger('status_id')
                    ->nullable();
                $table->uuid('group_id')
                    ->nullable();
                $table->foreign('group_id')
                    ->references('id')
                    ->on('tasks_groups')
                    ->onDelete('cascade');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
                $table->foreign('status_id')
                    ->references('id')
                    ->on('tasks_statuses')
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
        Schema::dropIfExists('tasks');
    }
}
