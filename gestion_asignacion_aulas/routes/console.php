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
