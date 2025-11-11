<?php

namespace App\Livewire\AcademicLogistics\Attendance;

use App\Models\Assignment;
use App\Models\AttendanceRecord;
use App\Models\QrToken;
use App\Models\AcademicManagement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
class AttendanceQrManager extends Component
{
    public $showModal = false;
    public $currentAssignment = null;
    public $currentToken = null;
    public $qrCode = null;
    public $expiresAt = null;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function render(): View
    {
        $todayAssignments = $this->getTodayAssignments();

        // Separar en pendientes y finalizadas
        $pendingAssignments = collect();
        $completedAssignments = collect();

        foreach($todayAssignments as $assignment) {
            $attendanceRecord = $assignment->attendanceRecords->first();
            $isMarked = $attendanceRecord && $attendanceRecord->isMarked();

            $dayName = trim($assignment->daySchedule->day->name);
            $today = now();
            $weekStart = $today->copy()->startOfWeek();
            $dayNumberMap = ['Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7];
            $targetDayNumber = $dayNumberMap[$dayName] ?? 1;
            $classDate = $weekStart->copy()->addDays($targetDayNumber - 1);
            $classDateTime = Carbon::parse($classDate->format('Y-m-d') . ' ' . $assignment->daySchedule->schedule->end);
            $expired = now()->greaterThan($classDateTime);

            if ($isMarked || $expired) {
                $completedAssignments->push($assignment);
            } else {
                $pendingAssignments->push($assignment);
            }
        }

        return view('livewire.academic-logistics.attendance.attendance-qr-manager', [
            'assignments' => $todayAssignments,
            'pendingAssignments' => $pendingAssignments,
            'completedAssignments' => $completedAssignments
        ]);
    }

    /**
     * Obtener las asignaciones de la semana actual para el docente autenticado
     */
    private function getTodayAssignments()
    {
        $userId = Auth::id();

        // Obtener el periodo académico activo
        $activeAcademicManagement = AcademicManagement::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$activeAcademicManagement) {
            return collect([]);
        }

        $assignments = Assignment::with([
            'userSubject.subject',
            'userSubject.user',
            'group',
            'daySchedule.day',
            'daySchedule.schedule',
            'classroom',
            'attendanceRecords' => function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->whereBetween('created_at', [
                          now()->startOfWeek(),
                          now()->endOfWeek()
                      ]);
            }
        ])
        ->whereHas('userSubject', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->where('academic_management_id', $activeAcademicManagement->id)
        ->get()
        ->sortBy(function ($assignment) {
            // Calcular la fecha real de la clase en la semana actual
            $dayName = trim($assignment->daySchedule->day->name);
            $dayNumberMap = [
                'Monday' => 1,
                'Tuesday' => 2,
                'Wednesday' => 3,
                'Thursday' => 4,
                'Friday' => 5,
                'Saturday' => 6,
                'Sunday' => 7
            ];

            $targetDayNumber = $dayNumberMap[$dayName] ?? 1;
            $weekStart = now()->startOfWeek(); // Lunes de esta semana
            $classDate = $weekStart->copy()->addDays($targetDayNumber - 1);
            $startTime = $assignment->daySchedule->schedule->start;

            // Combinar fecha y hora para ordenamiento cronológico
            $dateTime = $classDate->format('Y-m-d') . ' ' . $startTime;

            return $dateTime;
        });

        return $assignments;
    }

    /**
     * Generar o mostrar QR para una asignación
     */
    public function generateQr($assignmentId)
    {
        try {
            $assignment = Assignment::with([
                'daySchedule.day',
                'daySchedule.schedule',
                'userSubject.subject'
            ])->findOrFail($assignmentId);

            // Validar que la asignación pertenezca al docente autenticado
            if ($assignment->userSubject->user_id !== Auth::id()) {
                session()->flash('error', '🚫 No tienes permisos para esta asignación.');
                return;
            }

            // Validar horario (5 min antes hasta fin de clase)
            $validation = $this->validateClassTime($assignment);
            if (!$validation['valid']) {
                session()->flash('error', $validation['message']);
                return;
            }

            // Buscar o crear registro de asistencia para esta semana
            $attendanceRecord = $this->getOrCreateAttendanceRecord($assignment);

            // Buscar o generar token QR
            $qrToken = $this->getOrCreateQrToken($attendanceRecord, $assignment);

            // Generar código QR en SVG (no requiere extensiones)
            $url = route('attendance.scan', [
                'assignment' => $assignmentId,
                'token' => $qrToken->token
            ]);

            $this->qrCode = base64_encode(QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate($url));

            $this->currentAssignment = $assignment;
            $this->currentToken = $qrToken;
            $this->expiresAt = $qrToken->expires_at->format('H:i A');
            $this->showModal = true;

        } catch (\Exception $e) {
            session()->flash('error', 'Error al generar QR: ' . $e->getMessage());
        }
    }

    /**
     * Validar si la clase está en el horario permitido
     */
    private function validateClassTime($assignment): array
    {
        $currentTime = now();
        $scheduleStart = $assignment->daySchedule->schedule->start;
        $scheduleEnd = $assignment->daySchedule->schedule->end;

        // Combinar fecha actual con horas de la clase
        $classStartTime = Carbon::parse(now()->format('Y-m-d') . ' ' . $scheduleStart);
        $classEndTime = Carbon::parse(now()->format('Y-m-d') . ' ' . $scheduleEnd);

        // 5 minutos antes del inicio
        $fiveMinutesBefore = $classStartTime->copy()->subMinutes(5);

        if ($currentTime->lt($fiveMinutesBefore)) {
            return [
                'valid' => false,
                'message' => '⛔ No hay clase activa en este momento. La clase comienza a las ' . $classStartTime->format('H:i')
            ];
        }

        if ($currentTime->gt($classEndTime)) {
            return [
                'valid' => false,
                'message' => '⛔ La clase ya finalizó (terminó a las ' . $classEndTime->format('H:i') . ').'
            ];
        }

        return ['valid' => true];
    }

    /**
     * Obtener o crear registro de asistencia para la semana actual
     */
    private function getOrCreateAttendanceRecord($assignment)
    {
        $existingRecord = AttendanceRecord::where('assignment_id', $assignment->id)
            ->where('user_id', Auth::id())
            ->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->first();

        if ($existingRecord) {
            return $existingRecord;
        }

        return AttendanceRecord::create([
            'assignment_id' => $assignment->id,
            'user_id' => Auth::id(),
            'status' => 'absent',
            'scan_time' => null,
            'finish_time' => null,
        ]);
    }

    /**
     * Obtener o crear token QR
     */
    private function getOrCreateQrToken($attendanceRecord, $assignment)
    {
        $existingToken = $attendanceRecord->qrToken;

        // Si el token existe y es válido, reutilizarlo
        if ($existingToken && $existingToken->isValid()) {
            return $existingToken;
        }

        // Si existe pero expiró o fue usado, eliminarlo
        if ($existingToken) {
            $existingToken->delete();
        }

        // Calcular hora de expiración (fin de la clase)
        $scheduleEnd = $assignment->daySchedule->schedule->end;
        $expiresAt = Carbon::parse(now()->format('Y-m-d') . ' ' . $scheduleEnd);

        // Crear nuevo token
        return QrToken::create([
            'attendance_id' => $attendanceRecord->id,
            'token' => Str::uuid()->toString(),
            'expires_at' => $expiresAt,
            'used' => false,
        ]);
    }

    /**
     * Regenerar QR
     */
    public function regenerateQr()
    {
        if (!$this->currentAssignment) {
            return;
        }

        // Eliminar token anterior
        if ($this->currentToken) {
            QrToken::find($this->currentToken->id)?->delete();
        }

        // Generar nuevo QR
        $this->generateQr($this->currentAssignment->id);

        session()->flash('message', '🔄 QR regenerado correctamente.');
    }

    /**
     * Descargar QR como imagen SVG (compatible con todos los dispositivos móviles)
     */
    public function downloadQr()
    {
        if (!$this->currentToken) {
            return;
        }

        $url = route('attendance.scan', [
            'assignment' => $this->currentAssignment->id,
            'token' => $this->currentToken->token
        ]);

        // Generar QR en formato SVG (compatible con móviles, no requiere extensiones)
        $qrCodeSvg = QrCode::size(500)
            ->format('svg')
            ->errorCorrection('H')
            ->generate($url);

        $filename = 'QR_Asistencia_' . $this->currentAssignment->userSubject->subject->code . '_' . now()->format('Y-m-d') . '.svg';

        return response()->streamDownload(function () use ($qrCodeSvg) {
            echo $qrCodeSvg;
        }, $filename, ['Content-Type' => 'image/svg+xml']);
    }

    /**
     * Cerrar modal
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->currentAssignment = null;
        $this->currentToken = null;
        $this->qrCode = null;
        $this->expiresAt = null;
    }
}
