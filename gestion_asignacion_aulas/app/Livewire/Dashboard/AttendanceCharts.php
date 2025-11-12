<?php

namespace App\Livewire\Dashboard;

use App\Models\AttendanceRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AttendanceCharts extends Component
{
    public function render(): View
    {
        // Top 10 Docentes por Asistencia
        $topDocentes = $this->getTopDocentes();

        // Distribución de Estados
        $distribucionEstados = $this->getDistribucionEstados();

        // Tendencia Semanal
        $tendenciaSemanal = $this->getTendenciaSemanal();

        return view('livewire.dashboard.attendance-charts', [
            'topDocentes' => $topDocentes,
            'distribucionEstados' => $distribucionEstados,
            'tendenciaSemanal' => $tendenciaSemanal,
        ]);
    }

    private function getTopDocentes()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $docentes = User::select('users.id', 'users.name')
            ->join('attendance_records', 'users.id', '=', 'attendance_records.user_id')
            ->whereBetween('attendance_records.created_at', [$startOfMonth, $endOfMonth])
            ->selectRaw('
                COUNT(CASE WHEN attendance_records.status IN (\'on_time\', \'late\') THEN 1 END) as asistencias,
                COUNT(*) as total_sesiones,
                CASE
                    WHEN COUNT(*) > 0 THEN ROUND((COUNT(CASE WHEN attendance_records.status IN (\'on_time\', \'late\') THEN 1 END) * 100.0 / COUNT(*)), 1)
                    ELSE 0
                END as porcentaje
            ')
            ->groupBy('users.id', 'users.name')
            ->having(DB::raw('COUNT(*)'), '>', 0)
            ->orderByDesc('porcentaje')
            ->limit(10)
            ->get();

        return [
            'labels' => $docentes->pluck('name')->toArray(),
            'data' => $docentes->pluck('porcentaje')->toArray(),
            'colors' => $docentes->map(function ($docente) {
                if ($docente->porcentaje >= 90) return '#10b981'; // green
                if ($docente->porcentaje >= 75) return '#fbbf24'; // yellow
                return '#ef4444'; // red
            })->toArray()
        ];
    }

    private function getDistribucionEstados()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $estados = AttendanceRecord::selectRaw('
                status,
                COUNT(*) as total
            ')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupBy('status')
            ->get();

        $aTiempo = $estados->where('status', 'on_time')->first()->total ?? 0;
        $tarde = $estados->where('status', 'late')->first()->total ?? 0;
        $ausente = $estados->where('status', 'absent')->first()->total ?? 0;
        $total = $aTiempo + $tarde + $ausente;

        return [
            'labels' => ['A tiempo', 'Tarde', 'Ausente'],
            'data' => [$aTiempo, $tarde, $ausente],
            'total' => $total,
            'colors' => ['#10b981', '#fbbf24', '#ef4444']
        ];
    }

    private function getTendenciaSemanal()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $datos = AttendanceRecord::selectRaw('
                DATE(created_at) as fecha,
                COUNT(CASE WHEN status = \'on_time\' THEN 1 END) as a_tiempo,
                COUNT(CASE WHEN status = \'late\' THEN 1 END) as retrasos,
                COUNT(CASE WHEN status = \'absent\' THEN 1 END) as ausentes,
                COUNT(*) as total
            ')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('fecha')
            ->get();

        // Crear array con todos los días de la semana
        $diasSemana = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
        $asistencias = array_fill(0, 7, 0);
        $retrasos = array_fill(0, 7, 0);

        foreach ($datos as $dato) {
            $fecha = Carbon::parse($dato->fecha);
            $dayIndex = $fecha->dayOfWeek === 0 ? 6 : $fecha->dayOfWeek - 1; // Ajustar domingo

            if ($dato->total > 0) {
                // Porcentaje de asistencias (a tiempo + tarde)
                $totalAsistencias = $dato->a_tiempo + $dato->retrasos;
                $asistencias[$dayIndex] = round(($totalAsistencias / $dato->total) * 100, 1);

                // Porcentaje de retrasos
                $retrasos[$dayIndex] = round(($dato->retrasos / $dato->total) * 100, 1);
            }
        }

        return [
            'labels' => $diasSemana,
            'asistencias' => $asistencias,
            'retrasos' => $retrasos
        ];
    }
}
