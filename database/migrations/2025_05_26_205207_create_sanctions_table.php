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
        Schema::create('sanctions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('matricule');  // Add this line

            $table->enum('type', ['consigne', 'arret', 'blame', 'avert']);
            $table->foreign('matricule')->references('matricule')->on('students')->onDelete('cascade');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->string('motif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('senctions');
    }
};
