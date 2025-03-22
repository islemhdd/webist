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

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('password');

            $table->string("name");
            $table->enum("grade", ["1", "2", "3"]);
            $table->timestamps();
            $table->timestamp("deleted_at")->nullable();

            $table->foreignId("section_id")->constrained("sections");
            $table->rememberToken();
            $table->boolean("consigned")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
