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
        Schema::table('liste_rdvs', function (Blueprint $table) {
            $table->string('nom')->after('matricule');
            $table->string('prenom')->after('nom');
            $table->unsignedInteger('section_id')->after('prenom');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liste_rdvs', function (Blueprint $table) {
            $table->dropColumn(['nom', 'prenom', 'section_id']);
        });
    }
};
