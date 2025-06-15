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
        Schema::create('students', function (Blueprint $table) {
            $table->unsignedBigInteger('matricule')->primary();
            $table->string('nom');
            $table->string('prenom');
            // Combined full name field
            $table->enum('grade', ['1', '2', '3']);
            $table->unsignedBigInteger('section_id');
            $table->boolean('consigned')->default(false);
            $table->string('choix')->nullable();
            $table->timestamps();


            // Foreign key
            $table->foreign('section_id')
                ->references('id')
                ->on('sections')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
