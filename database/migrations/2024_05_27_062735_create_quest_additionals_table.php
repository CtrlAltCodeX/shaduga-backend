<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quest_additionals', function (Blueprint $table) {
            $table->id();
            $table->integer('quest_id')->nullable();
            $table->string('link')->nullable();
            $table->string('partnership')->nullable();
            $table->string('number_invitation')->nullable();
            $table->string('description')->nullable();
            $table->string('endpoint')->nullable();
            $table->string('api_key')->nullable();
            $table->string('methods')->nullable();
            $table->string('task_type')->nullable();
            $table->string('request_type')->nullable();
            $table->string('correct_answer')->nullable();
            $table->string('star')->nullable();
            $table->string('steps')->nullable();
            $table->string('labels')->nullable();
            $table->string('files')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quest_additionals');
    }
};
