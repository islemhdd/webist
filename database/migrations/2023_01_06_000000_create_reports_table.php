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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('officer_id');
            $table->string('status')->nullable();
            $table->string('title')->nullable();
            $table->text('corps')->nullable();
            $table->boolean('is_medical')->default(0);
            $table->unsignedBigInteger('destination')->nullable();
            $table->tinyInteger('refused')->default(0); // 0: not refused, 1: refused, 2: accepted
            $table->text('motif')->nullable(); // Reason for refusal
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('student_id')->references('matricule')->on('students')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('officer_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
