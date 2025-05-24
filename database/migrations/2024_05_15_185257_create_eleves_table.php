<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('Students', function (Blueprint $table) {
            $table->integer('matricule', true, true)->length(7);
            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->integer('section', false, true)->length(3);

            $table->primary('matricule');
        });
    }

    public function down()
    {
        Schema::dropIfExists('Students');
    }
};
