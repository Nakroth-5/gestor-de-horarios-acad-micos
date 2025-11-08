<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();

            // Relación con user_subjects (docente-materia ya asignados)
            $table->foreignId('user_subject_id')
                ->constrained('user_subjects')
                ->onDelete('cascade');

            // Subject_id redundante para validación eficiente
            // Se llenará automáticamente desde user_subjects
            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->onDelete('cascade');

            // Relación con grupo
            $table->foreignId('group_id')
                ->constrained()
                ->onDelete('cascade');

            // Relación con horario (día + hora)
            $table->foreignId('day_schedule_id')
                ->constrained()
                ->onDelete('cascade');

            // Relación con salón/aula
            $table->foreignId('classroom_id')
                ->constrained('classrooms')
                ->onDelete('cascade');

            // Relación con periodo académico
            $table->foreignId('academic_management_id')
                ->constrained('academic_management')
                ->onDelete('cascade');


            // ÍNDICES ÚNICOS COMPUESTOS

            // 1. Evita que un salón esté ocupado en el mismo horario
            // Incluimos academic_management_id para que sea por periodo
            $table->unique(
                ['classroom_id', 'day_schedule_id', 'academic_management_id'],
                'unique_classroom_schedule'
            );

            // 2. Evita que una materia se asigne al mismo grupo más de una vez
            // Incluimos academic_management_id para que sea por periodo
            $table->unique(
                ['subject_id', 'group_id', 'academic_management_id'],
                'unique_subject_group'
            );

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
