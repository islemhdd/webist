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
        Schema::create('rhps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('officer_id');
            $table->date('date_assignation');
            $table->enum('periode', ['matin', 'apres_midi']); // matin: 8:00-12:20, apres_midi: 13:30-16:20
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('officer_id')->references('id')->on('users')->onDelete('cascade');

            // Unique constraint pour éviter les doublons
            $table->unique(['date_assignation', 'periode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rhps');
    }
};
