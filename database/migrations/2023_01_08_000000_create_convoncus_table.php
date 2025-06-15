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
        Schema::create('convoncus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('matricule');
            $table->boolean('psy')->default(false);
            $table->boolean('medGen')->default(false);
            $table->boolean('chirDent')->default(false);
            $table->boolean('avisSpe')->default(false);
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
        Schema::dropIfExists('convoncus');
    }
};
