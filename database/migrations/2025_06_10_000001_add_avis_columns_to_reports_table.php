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
        Schema::table('reports', function (Blueprint $table) {
            $table->text('AvisChef_de_compagnie')->nullable();
            $table->text('AvisChef_de_brigade')->nullable();
            $table->text('AvisChef_de_batallaint')->nullable();
            $table->text('AvisChef_division')->nullable();
            $table->text('AvisMedecin')->nullable();
            $table->text('AvisDirecteur_général')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(
            'reports',
            function (Blueprint $table) {
                $table->dropColumn('AvisChef_de_compagnie');
                $table->dropColumn('AvisChef_de_brigade');
                $table->dropColumn('AvisChef_de_batallaint');
                $table->dropColumn('AvisChef_division');
                $table->dropColumn('AvisMedecin');
                $table->dropColumn('AvisDirecteur_général');
                try {
                    $table->dropColumn('AvisChef_de_compagnie_at');
                    $table->dropColumn('AvisChef_de_brigade_at');
                    $table->dropColumn('AvisChef_de_batallaint_at');
                    $table->dropColumn('AvisChef_division_at');
                    $table->dropColumn('AvisMedecin_at');
                    $table->dropColumn('AvisDirecteur_général_at');
                } catch (\Exception $e) {
                    // Handle the exception if the columns do not exist
                }
            }
        );
    }
};
