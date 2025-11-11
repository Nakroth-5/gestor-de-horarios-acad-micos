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
        Schema::create('university_careers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 20)->unique();
            $table->string('study_level', 50); // Licenciatura, Maestría, Doctorado, etc.
            $table->integer('duration_years'); // Duración en años
            $table->string('faculty', 100); // Facultad
            $table->string('language', 50)->default('Español'); // Idioma
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('university_careers');
    }
};
