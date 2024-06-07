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
        Schema::create('modules', function (Blueprint $table) {
            $table->id('id');
            $table->string('title');
            $table->string('desc');
            $table->unsignedBigInteger('community_id');
            $table->timestamps();

            // Define the foreign key constraint
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
        Schema::drop('modules');
    }
};
