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
        Schema::create('subject_university_career', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('university_career_id')->constrained('university_careers')->onDelete('cascade');
            $table->integer('semester')->nullable(); // Semestre en el que se imparte
            $table->boolean('is_required')->default(true); // Materia obligatoria o electiva
            $table->timestamps();
            
            // Evitar duplicados
            $table->unique(['subject_id', 'university_career_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_university_career');
    }
};
