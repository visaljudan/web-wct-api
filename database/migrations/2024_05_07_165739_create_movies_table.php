<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tv_show_id')->nullable();
            $table->foreign('tv_show_id')->references('id')->on('tv_shows')->onDelete('cascade');
            $table->string('title');
            $table->text('overview')->nullable();
            $table->integer('run_time')->nullable();
            $table->date('release_date')->nullable();
            // $table->string('poster_image_file')->nullable();
            // $table->string('poster_image_url')->nullable();
            // $table->string('cover_image_file')->nullable();
            // $table->string('cover_image_url')->nullable();
            $table->string('poster_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->integer('total_raters')->default(0);
            $table->integer('total_ratings')->default(0);
            $table->decimal('average_rating', 3, 1)->default(0);
            $table->integer('popularity')->default(0);
            $table->string('terms_status')->nullable()->default("public");
            $table->string('upload_status')->nullable();
            $table->dateTime('last_upload_date')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
