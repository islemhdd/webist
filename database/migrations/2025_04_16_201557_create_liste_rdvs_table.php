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
        Schema::create('liste_rdvs', function (Blueprint $table) {
            $table->unsignedInteger('matricule');
            // Utiliser 'string' si 'matricule' est une chaîne
            $table->enum('motif', ['consultation', 'urgences']);
            $table->string('service');
            $table->dateTime('date');
            $table->timestamps();

            $table->foreign('matricule')
                ->references('matricule')
                ->on('Students')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liste_rdvs');
    }
};
