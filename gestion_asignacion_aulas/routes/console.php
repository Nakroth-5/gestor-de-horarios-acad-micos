<?php

use App\Models\QrToken;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Limpieza automática de tokens QR expirados (más de 2 días de antigüedad)
Schedule::call(function () {
    $deleted = QrToken::where('expires_at', '<', now()->subDays(2))->delete();
    if ($deleted > 0) {
        \Log::info("Limpieza de QR tokens: {$deleted} tokens eliminados");
    }
})->daily()->at('02:00')->name('cleanup-expired-qr-tokens');

// Generar registros de asistencia para todas las clases del día
Schedule::command('attendance:generate-daily')
    ->dailyAt('05:00') // A las 5:00 AM se crean los registros del día
    ->name('generate-daily-attendance-records')
    ->withoutOverlapping()
    ->onSuccess(function () {
        \Log::info('Registros de asistencia diarios generados exitosamente');
    })
    ->onFailure(function () {
        \Log::error('Error al generar registros de asistencia diarios');
    });

// Marcar automáticamente asistencias ausentes al final de cada hora
Schedule::command('attendance:mark-absent')
    ->everyThreeMinutes()
    ->between('06:00', '22:00') // Solo en horario laboral/académico
    ->name('mark-absent-attendances')
    ->withoutOverlapping()
    ->onSuccess(function () {
        \Log::info('Comando attendance:mark-absent ejecutado exitosamente');
    })
    ->onFailure(function () {
        \Log::error('Error al ejecutar comando attendance:mark-absent');
    });
