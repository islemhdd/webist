<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifier la colonne type_medecin pour inclure chef_médecin
        DB::statement("ALTER TABLE liste_rdvs MODIFY COLUMN type_medecin ENUM('médecin générale','dentiste','psycho','chef_médecin') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre l'ancien ENUM
        DB::statement("ALTER TABLE liste_rdvs MODIFY COLUMN type_medecin ENUM('médecin générale','dentiste','psycho') NOT NULL");
    }
};
