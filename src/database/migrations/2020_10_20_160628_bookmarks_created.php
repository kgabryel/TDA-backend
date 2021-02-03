<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BookmarksCreated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'bookmarks',
            function(Blueprint $table) {
                $table->id();
                $table->string('title', 50);
                $table->text('href');
                $table->string('background_color', 7);
                $table->string('text_color', 7);
                $table->text('icon')
                    ->nullable();
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
                $table->boolean('assigned_to_dashboard')
                    ->default(false);
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
        Schema::dropIfExists('bookmarks');
    }
}
