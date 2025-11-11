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
        Schema::create('special_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario solicitante
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // Administrador que aprueba
            $table->string('event_type'); // Tipo: defensa, examen_especial, evento_academico, reunion, otro
            $table->string('title'); // Título del evento
            $table->text('description')->nullable(); // Descripción detallada
            $table->date('reservation_date'); // Fecha de la reserva
            $table->time('start_time'); // Hora de inicio
            $table->time('end_time'); // Hora de fin
            $table->enum('status', ['pendiente', 'aprobada', 'rechazada', 'cancelada'])->default('pendiente');
            $table->text('rejection_reason')->nullable(); // Motivo de rechazo
            $table->integer('estimated_attendees')->nullable(); // Número estimado de asistentes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_reservations');
    }
};
