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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->integer('bat');
            $table->integer('companie');
            $table->integer('num');

            $table->unsignedBigInteger('officer_id')->nullable();
            $table->timestamps();

            $table->foreign('officer_id')->references('id')->on('users')
                ->onDelete('set null');
        });

        // We need to add triggers outside of Laravel's schema builder
        DB::unprepared('
            CREATE TRIGGER before_insert_section BEFORE INSERT ON sections FOR EACH ROW
            BEGIN
                SET NEW.id = NEW.bat * 100 + NEW.companie * 10 + NEW.num;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER before_update_section BEFORE UPDATE ON sections FOR EACH ROW
            BEGIN
                SET NEW.id = NEW.bat * 100 + NEW.companie * 10 + NEW.num;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS before_insert_section');
        DB::unprepared('DROP TRIGGER IF EXISTS before_update_section');
        Schema::dropIfExists('sections');
    }
};
