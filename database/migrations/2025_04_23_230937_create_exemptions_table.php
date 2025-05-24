<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('exemptions', function (Blueprint $table) {
            $table->unsignedInteger('matricule');
            $table->string('motif');
            $table->date('date_debut');
            $table->date('date_fin');

            $table->foreign('matricule')
                ->references('matricule')
                ->on('Students')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exemptions');
    }
};
