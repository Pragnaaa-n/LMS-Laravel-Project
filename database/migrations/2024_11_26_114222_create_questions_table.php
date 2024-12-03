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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade')
                ->onUpdate('cascade')->nullable();
            $table->unsignedBigInteger('exam_id');
            $table->foreign('exam_id')
                ->references('id')
                ->on('exam_types')
                ->onDelete('cascade')
                ->onUpdate('cascade')->nullable();
            $table->unsignedBigInteger('test_type_id');
            $table->foreign('test_type_id')
                    ->references('id')
                    ->on('test_types')
                    ->onDelete('cascade')
                    ->onUpdate('cascade')->nullable();
            $table->string('question');
            $table->string('option1')->nullable();
            $table->string('option2')->nullable();
            $table->string('option3')->nullable();
            $table->string('option4')->nullable();
            $table->string('option5')->nullable();
            $table->string('option6')->nullable();
            $table->string('correct_answer')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
