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
        Schema::create('members', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('community_id');
            $table->integer('user_id');
            $table->date('join_date');
            $table->tinyInteger('status');
            $table->string('role');
            $table->string('last_active');
            $table->timestamps();

            $table->foreign('community_id')
                ->references('id')
                ->on('communities')
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
        Schema::drop('members');
    }
};
