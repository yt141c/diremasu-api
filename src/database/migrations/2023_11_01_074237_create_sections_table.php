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
    Schema::create('sections', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('course_id')->unsigned();
      $table->integer('order')->unsigned();
      $table->string('name', 255);
      $table->softDeletes();
      $table->timestamps();

      $table->index('course_id');
      $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('sections');
  }
};
