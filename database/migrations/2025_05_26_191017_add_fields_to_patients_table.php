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
        Schema::table('patients', function (Blueprint $table) {
            // Change valider from boolean to tinyInteger (0=non validé, 1=validé, 2=supprimé)
            

            // Add new fields
            $table->text('motif_suppression')->nullable();
            $table->enum('type_medecin', ['médecin générale', 'dentiste', 'psycho'])->nullable();
            $table->text('avis_medecin')->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Revert valider back to boolean
            $table->boolean('valider')->default(false)->change();

            // Drop the new fields
            $table->dropColumn(['motif_suppression', 'type_medecin', 'avis_medecin']);
        });
    }
};
