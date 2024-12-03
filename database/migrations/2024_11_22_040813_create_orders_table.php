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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date('date')->default(DB::raw('CURRENT_DATE')); 
            $table->unsignedBigInteger('exam_type_id'); 
            $table->foreign('exam_type_id')
                 ->references('id')
                 ->on('exam_types')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');
            $table->unsignedBigInteger('student_id'); 
            $table->foreign('student_id')
                 ->references('id')
                 ->on('students')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');
            $table->string('email'); 
            $table->string('mobile'); 
            $table->date('start_validity_date'); 
            $table->date('expire_validity_date'); 
            $table->string('receipt')->nullable(); 
            $table->boolean('status')->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
