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
        Schema::create('requested_movie_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requested_movie_id');
            $table->foreign('requested_movie_id')->references('id')->on('requested_movies')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('requested_movies')->onDelete('cascade');
            $table->text('response_message');
            $table->string('response_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requested_movie_responses');
    }
};
