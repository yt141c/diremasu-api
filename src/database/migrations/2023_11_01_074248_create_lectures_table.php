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
        Schema::create('lectures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('section_id')->unsigned();
            $table->integer('order')->unsigned();
            $table->string('name', 255);
            $table->string('video_url', 255);
            $table->longtext('description');
            $table->string('slug', 255)->nullable();
            $table->string('prev_lecture_slug', 255)->nullable();
            $table->string('next_lecture_slug', 255)->nullable();
            $table->boolean('is_premium')->default(1);
            $table->boolean('published')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index('section_id');
            $table->unique(['slug']);
            $table->index(['section_id', 'order']);

            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lectures');
    }
};
