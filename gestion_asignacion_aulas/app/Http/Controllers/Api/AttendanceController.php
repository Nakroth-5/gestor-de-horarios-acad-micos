<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AttendanceRecord;
use App\Models\QrToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    /**
     * Marca la asistencia de un estudiante escaneando un código QR
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAttendance(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|uuid',
            'assignment_id' => 'required|exists:assignments,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 1. Verificar que el token existe (con su registro de asistencia)
            $qrToken = QrToken::with('attendanceRecord')->where('token', $request->token)->first();

            if (!$qrToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Código QR inválido'
                ], 404);
            }

            // 2. Verificar que el token tiene un registro de asistencia asociado
            if (!$qrToken->attendanceRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Código QR sin registro de asistencia asociado'
                ], 400);
            }

            // 3. Verificar que el token no ha sido usado
            if ($qrToken->used) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este código QR ya ha sido usado'
                ], 400);
            }

            // 4. Verificar que el token no ha expirado
            if ($qrToken->isExpired()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este código QR ha expirado'
                ], 400);
            }

            // 5. Verificar que el attendance_record coincide con el assignment_id
            $attendanceRecord = $qrToken->attendanceRecord;

            if ($attendanceRecord->assignment_id != $request->assignment_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'El código QR no corresponde a esta clase'
                ], 400);
            }

            // 6. Obtener el assignment para validaciones de horario
            $assignment = Assignment::with(['daySchedule.schedule', 'daySchedule.day'])
                ->findOrFail($request->assignment_id);

            // 7. Verificar que el día actual coincide con el día de la clase
            // Obtener el día actual en inglés para comparar con la BD
            $currentDayName = now()->locale('en')->dayName; // Usar inglés
            $assignmentDayName = trim($assignment->daySchedule->day->name); // Limpiar espacios

            // Normalizar nombres de días para comparación
            $currentDayNormalized = strtolower(trim($currentDayName));
            $assignmentDayNormalized = strtolower(trim($assignmentDayName));

            if ($currentDayNormalized !== $assignmentDayNormalized) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este código QR no es válido para el día de hoy'
                ], 400);
            }

            // 8. Validar que estamos dentro del horario permitido
            // Permitir desde 5 minutos antes hasta el final de la clase
            $now = Carbon::now();
            $scheduleStart = Carbon::parse($assignment->daySchedule->schedule->start);
            $scheduleEnd = Carbon::parse($assignment->daySchedule->schedule->end);

            // Ajustar las horas al día actual
            $classStart = Carbon::parse($now->format('Y-m-d') . ' ' . $scheduleStart->format('H:i:s'));
            $classEnd = Carbon::parse($now->format('Y-m-d') . ' ' . $scheduleEnd->format('H:i:s'));
            $allowedStart = $classStart->copy()->subMinutes(5);

            if ($now->lt($allowedStart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aún no es hora de marcar asistencia. La clase inicia a las ' . $classStart->format('H:i')
                ], 400);
            }

            if ($now->gt($classEnd)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El horario para marcar asistencia ha finalizado'
                ], 400);
            }

            // 9. Verificar que el usuario no haya marcado ya la asistencia
            if ($attendanceRecord->isMarked()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya has marcado tu asistencia para esta clase'
                ], 400);
            }

            // 10. Calcular el estado de asistencia
            // on_time: si llega hasta 15 minutos después de la hora de inicio
            // late: si llega más de 15 minutos tarde pero antes del final de clase
            $minutesLate = $now->diffInMinutes($classStart, false);

            $status = 'on_time';
            if ($minutesLate < -15) { // Si minutesLate es negativo, llegó tarde
                $status = 'late';
            }

            // 11. Actualizar el registro de asistencia
            $attendanceRecord->update([
                'scan_time' => $now,
                'status' => $status,
                'finish_time' => null, // Se puede implementar después si se desea
            ]);

            // 12. Marcar el token como usado
            $qrToken->markAsUsed();

            // 13. Responder con éxito
            return response()->json([
                'success' => true,
                'message' => $status === 'on_time'
                    ? '¡Asistencia registrada correctamente!'
                    : 'Asistencia registrada (llegada tarde)',
                'data' => [
                    'status' => $status,
                    'scan_time' => $attendanceRecord->scan_time->format('Y-m-d H:i:s'),
                    'subject' => $assignment->userSubject->subject->name,
                    'group' => $assignment->group->name,
                    'classroom' => 'Aula ' . $assignment->classroom->number,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar la asistencia: ' . $e->getMessage()
            ], 500);
        }
    }
}
