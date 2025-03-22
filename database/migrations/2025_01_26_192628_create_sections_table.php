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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();

            $table->enum("num", [1, 2, 3]);
            $table->enum("companie", [1, 2, 3, 4, 5, 6]);
            $table->enum("bat", [1, 2, 3]);

            $table->foreignId("officer_id")->constrained("officers");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
