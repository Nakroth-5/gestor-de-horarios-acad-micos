<?php

namespace App\Livewire\Dashboard;

use App\Models\AttendanceRecord;
use App\Models\Assignment;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class KpiCards extends Component
{
    public function mount()
    {
        // Ejecutar el comando de marcar ausencias automáticamente al cargar el dashboard
        $this->markAbsentAttendances();
    }

    public function render(): View
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Asistencias de hoy (registros con asistencia marcada)
        $asistenciasHoy = AttendanceRecord::whereDate('created_at', $today)
            ->whereIn('status', ['on_time', 'late'])
            ->count();

        $asistenciasAyer = AttendanceRecord::whereDate('created_at', $yesterday)
            ->whereIn('status', ['on_time', 'late'])
            ->count();

        // Retrasos de hoy
        $retrasosHoy = AttendanceRecord::whereDate('created_at', $today)
            ->where('status', 'late')
            ->count();

        $retrasosAyer = AttendanceRecord::whereDate('created_at', $yesterday)
            ->where('status', 'late')
            ->count();

        // Inasistencias de hoy
        $inasistenciasHoy = AttendanceRecord::whereDate('created_at', $today)
            ->where('status', 'absent')
            ->count();

        $inasistenciasAyer = AttendanceRecord::whereDate('created_at', $yesterday)
            ->where('status', 'absent')
            ->count();

        // Sesiones programadas esta semana
        // Contar las asignaciones del periodo académico activo que corresponden a esta semana
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $daysThisWeek = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            if ($date->lte($endOfWeek)) {
                $daysThisWeek[] = $dayNames[$date->dayOfWeek];
            }
        }

        $sesionesSemanales = Assignment::whereHas('academicManagement', function ($query) use ($today) {
            $query->where('start_date', '<=', $today)
                  ->where('end_date', '>=', $today);
        })
        ->whereHas('daySchedule.day', function ($query) use ($daysThisWeek) {
            $query->whereIn('name', $daysThisWeek);
        })
        ->count();

        // Calcular tendencias
        $tendenciaAsistencias = $this->calcularTendencia($asistenciasHoy, $asistenciasAyer);
        $tendenciaRetrasos = $this->calcularTendencia($retrasosHoy, $retrasosAyer);
        $tendenciaInasistencias = $this->calcularTendencia($inasistenciasHoy, $inasistenciasAyer);

        return view('livewire.dashboard.kpi-cards', [
            'asistenciasHoy' => $asistenciasHoy,
            'retrasosHoy' => $retrasosHoy,
            'inasistenciasHoy' => $inasistenciasHoy,
            'sesionesSemanales' => $sesionesSemanales,
            'tendenciaAsistencias' => $tendenciaAsistencias,
            'tendenciaRetrasos' => $tendenciaRetrasos,
            'tendenciaInasistencias' => $tendenciaInasistencias,
        ]);
    }

    private function calcularTendencia($hoy, $ayer): array
    {
        if ($ayer == 0) {
            return [
                'porcentaje' => $hoy > 0 ? 100 : 0,
                'direccion' => $hoy > 0 ? 'up' : 'neutral'
            ];
        }

        $cambio = (($hoy - $ayer) / $ayer) * 100;

        return [
            'porcentaje' => abs(round($cambio, 1)),
            'direccion' => $cambio > 0 ? 'up' : ($cambio < 0 ? 'down' : 'neutral')
        ];
    }

    /**
     * Marcar automáticamente las ausencias de clases expiradas
     */
    private function markAbsentAttendances()
    {
        try {
            \Artisan::call('attendance:mark-absent');
        } catch (\Exception $e) {
            \Log::error('Error al ejecutar attendance:mark-absent desde KPI: ' . $e->getMessage());
        }
    }
}
