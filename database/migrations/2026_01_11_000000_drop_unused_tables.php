<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Drop unused tables: arrets, patients, expulsions
     */
    public function up(): void
    {
        Schema::dropIfExists('arrets');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('expulsions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate arrets table
        Schema::create('arrets', function ($table) {
            $table->unsignedBigInteger('report_id');
            $table->unsignedBigInteger('sanction_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->primary(['report_id', 'sanction_id']);
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('sanction_id')->references('id')->on('sanctions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        // Recreate patients table
        Schema::create('patients', function ($table) {
            $table->id();
            $table->unsignedBigInteger('matricule');
            $table->string('motif')->nullable();
            $table->boolean('valider')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreign('matricule')->references('matricule')->on('students')->onDelete('cascade');
        });

        // Recreate expulsions table
        Schema::create('expulsions', function ($table) {
            $table->id();
            $table->unsignedBigInteger('matricule');
            $table->string('motif')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
            $table->foreign('matricule')->references('matricule')->on('students')->onDelete('cascade');
        });
    }
};
