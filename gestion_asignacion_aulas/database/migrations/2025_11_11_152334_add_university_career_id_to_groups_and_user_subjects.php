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
        // Agregar university_career_id a la tabla groups
        Schema::table('groups', function (Blueprint $table) {
            $table->foreignId('university_career_id')->nullable()->after('name')->constrained('university_careers')->onDelete('cascade');
        });

        // Agregar university_career_id a la tabla user_subjects y actualizar constraint
        Schema::table('user_subjects', function (Blueprint $table) {
            $table->foreignId('university_career_id')->nullable()->after('subject_id')->constrained('university_careers')->onDelete('cascade');
            
            // Eliminar la constraint única anterior (user_id, subject_id)
            $table->dropUnique(['user_id', 'subject_id']);
            
            // Agregar nueva constraint única que incluye university_career_id
            // Esto permite que un docente tenga la misma materia en diferentes carreras
            $table->unique(['user_id', 'subject_id', 'university_career_id'], 'user_subject_career_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign(['university_career_id']);
            $table->dropColumn('university_career_id');
        });

        Schema::table('user_subjects', function (Blueprint $table) {
            // Revertir: eliminar la nueva constraint
            $table->dropUnique('user_subject_career_unique');
            
            // Restaurar la constraint original
            $table->unique(['user_id', 'subject_id']);
            
            // Eliminar la columna
            $table->dropForeign(['university_career_id']);
            $table->dropColumn('university_career_id');
        });
    }
};
