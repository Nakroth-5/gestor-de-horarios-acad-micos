<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\QrToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceScanController extends Controller
{
    /**
     * Muestra la página de escaneo de QR y procesa la asistencia
     *
     * @param Request $request
     * @param Assignment $assignment
     * @return \Illuminate\View\View
     */
    public function scan(Request $request, Assignment $assignment)
    {
        // Validar que el token existe en la query string
        $token = $request->query('token');

        if (!$token) {
            return view('attendance.scan', [
                'success' => false,
                'message' => 'No se proporcionó un código QR válido',
                'assignment' => null,
            ]);
        }

        // Si el usuario no está autenticado, redirigir al login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para marcar tu asistencia')
                ->with('intended', route('attendance.scan', ['assignment' => $assignment->id, 'token' => $token]));
        }

        // Verificar que el QR token existe
        $qrToken = QrToken::where('token', $token)->first();

        if (!$qrToken) {
            return view('attendance.scan', [
                'success' => false,
                'message' => 'Código QR inválido o no encontrado',
                'assignment' => $assignment,
            ]);
        }

        // Llamar directamente al controlador de API 
        try {
            $attendanceController = new \App\Http\Controllers\Api\AttendanceController();

            // Crear request fake con los datos necesarios
            $fakeRequest = Request::create('/api/attendance/mark', 'POST', [
                'token' => $token,
                'assignment_id' => $assignment->id,
                'user_id' => Auth::id(),
            ]);

            $response = $attendanceController->markAttendance($fakeRequest);
            $data = $response->getData(true);

            return view('attendance.scan', [
                'success' => $data['success'] ?? false,
                'message' => $data['message'] ?? 'Error al procesar la solicitud',
                'assignment' => $assignment,
                'attendanceData' => $data['data'] ?? null,
            ]);

        } catch (\Exception $e) {
            return view('attendance.scan', [
                'success' => false,
                'message' => 'Error al marcar asistencia: ' . $e->getMessage(),
                'assignment' => $assignment,
            ]);
        }
    }
}
