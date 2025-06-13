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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('matricule');
            $table->tinyInteger('valider')->default(0); // 0: not validated, 1: validated, 2: deleted
            $table->timestamp('validated_at')->nullable();
            $table->string('motif_suppression')->nullable();
            $table->string('type_medecin')->nullable();
            $table->text('avis_medecin')->nullable();
            $table->timestamps();

            $table->foreign('matricule')
                ->references('matricule')
                ->on('students')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
