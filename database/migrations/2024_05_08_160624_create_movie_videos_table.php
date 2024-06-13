<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movie_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movie_id');
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            // $table->string('video_file')->nullable();
            // $table->string('video_url')->nullable();
            $table->string('video')->nullable();
            $table->unsignedInteger('season_number')->nullable();
            $table->unsignedInteger('episode_number')->nullable();
            $table->unsignedInteger('part_number')->nullable();
            $table->string('type');
            $table->boolean('official')->default(false);
            $table->boolean('subscription')->default(false);
            $table->date('subscription_start_date')->nullable();
            $table->date('subscription_end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movie_videos');
    }
};
