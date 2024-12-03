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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_type_id');
            $table->foreign('exam_type_id')
                ->references('id')
                ->on('exam_types')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->unsignedBigInteger('sub_category_id');
            $table->foreign('sub_category_id')
                ->references('id')
                ->on('sub_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('course_name');
            $table->string('description');
            $table->string('banner_image');
            $table->string('video_link');
            $table->string('vimeo_video');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
