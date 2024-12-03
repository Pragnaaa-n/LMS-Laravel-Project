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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_type_id');
            $table->foreign('test_type_id')
                ->references('id')
                ->on('test_types')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->unsignedBigInteger('exam_type_id');
            $table->foreign('exam_type_id')
                ->references('id')
                ->on('exam_types')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('vimeo_link')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('time_picker_start_date')->nullable();
            $table->string('time_picker_end_date')->nullable();
            $table->string('date_picker_start_time')->nullable();
            $table->string('date_picker_end_time')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
