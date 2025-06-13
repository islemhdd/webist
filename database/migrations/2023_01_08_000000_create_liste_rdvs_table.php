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
            $table->id();
            $table->string('matricule');
            $table->text('motif')->nullable();
            $table->string('service')->nullable();
            $table->dateTime('date');
            $table->timestamps();

            $table->foreign('matricule')->references('matricule')->on('students')
                ->onDelete('cascade')->onUpdate('cascade');
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
