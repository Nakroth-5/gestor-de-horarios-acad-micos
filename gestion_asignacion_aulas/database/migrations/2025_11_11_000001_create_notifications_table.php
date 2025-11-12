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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Tipo de notificación específica
            $table->enum('notification_type', [
                'attendance_pending',      // Asistencias por marcar
                'new_subject',            // Nueva materia asignada
                'schedule_change',        // Cambio de horario
                'direct_message',         // Comunicación directa
                'reservation_approved',   // Reserva aprobada
                'reservation_rejected',   // Reserva rechazada
                'reservation_permission'  // Permiso de reserva
            ]);

            // Prioridad para UI
            $table->enum('priority', ['info', 'important', 'urgent'])->default('info');

            // Indica si es automática o manual
            $table->boolean('is_automatic')->default(true);

            // Contenido
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Datos adicionales específicos del tipo

            // Control de lectura
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Índices para performance
            $table->index(['user_id', 'read_at']);
            $table->index(['user_id', 'is_automatic']);
            $table->index('created_at');
            $table->index('notification_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};