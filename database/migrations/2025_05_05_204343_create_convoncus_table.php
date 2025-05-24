<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('convoncus', function (Blueprint $table) {
            $table->unsignedInteger('matricule');
            $table->foreign('matricule')->references('matricule')->on('Students');
            $table->index('matricule'); // Ajout explicite d'un index
            $table->text('psy')->nullable();
            $table->text('medGen')->nullable();
            $table->text('chirDent')->nullable();
            $table->text('avisSpe')->nullable();
            $table->timestamps();
        });

        // Si nécessaire, force l'utilisation d'InnoDB
    }

    public function down(): void
    {
        Schema::dropIfExists('convoncus');
    }
};
