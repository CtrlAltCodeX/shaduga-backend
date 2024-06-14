<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('community_id');
            $table->integer('rating');
            $table->string('title');
            $table->string('body');
            $table->tinyInteger('status');
            $table->timestamps();

            $table->foreign('community_id')
                ->references('id')
                ->on('communities')
                ->onDelete('cascade'); // Optional: Define the action on delete

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // Optional: Define the action on delete
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reviews');
    }
};
