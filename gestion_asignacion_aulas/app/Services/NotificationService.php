<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Assignment;
use App\Models\AttendanceRecord;

class NotificationService
{
    /**
     * Crear notificación de asistencia pendiente
     */
    public function createAttendancePendingNotification(Assignment $assignment, User $teacher): Notification
    {
        $data = [
            'assignment_id' => $assignment->id,
            'subject_name' => $assignment->userSubject->subject->name,
            'group_name' => $assignment->group->name,
            'classroom_number' => $assignment->classroom->number,
            'teacher_name' => $teacher->name,
            'schedule' => $assignment->daySchedule->schedule->start . ' - ' . $assignment->daySchedule->schedule->end,
            'day' => $assignment->daySchedule->day->name,
        ];

        return Notification::create([
            'user_id' => $teacher->id,
            'notification_type' => 'attendance_pending',
            'priority' => 'urgent',
            'is_automatic' => true,
            'title' => 'Asistencia pendiente',
            'message' => "Clase finalizada sin registro de asistencia: {$data['subject_name']} - Grupo {$data['group_name']} en Aula {$data['classroom_number']}",
            'data' => $data,
        ]);
    }

    /**
     * Crear notificación de nueva materia asignada
     */
    public function createNewSubjectNotification(User $teacher, $subjectName, $groupName, $startDate): Notification
    {
        $data = [
            'teacher_name' => $teacher->name,
            'subject_name' => $subjectName,
            'group_name' => $groupName,
            'start_date' => $startDate,
        ];

        return Notification::create([
            'user_id' => $teacher->id,
            'notification_type' => 'new_subject',
            'priority' => 'important',
            'is_automatic' => true,
            'title' => 'Nueva materia asignada',
            'message' => "Se te ha asignado la materia {$subjectName} para el grupo {$groupName}. Fecha de inicio: {$startDate}",
            'data' => $data,
        ]);
    }

    /**
     * Crear notificación de cambio de horario
     */
    public function createScheduleChangeNotification(
        User $teacher,
        Assignment $assignment,
        $oldDay,
        $oldTime,
        $newDay,
        $newTime
    ): Notification {
        $data = [
            'assignment_id' => $assignment->id,
            'subject_name' => $assignment->userSubject->subject->name,
            'group_name' => $assignment->group->name,
            'old_day' => $oldDay,
            'old_time' => $oldTime,
            'new_day' => $newDay,
            'new_time' => $newTime,
            'classroom_number' => $assignment->classroom->number,
        ];

        return Notification::create([
            'user_id' => $teacher->id,
            'notification_type' => 'schedule_change',
            'priority' => 'important',
            'is_automatic' => true,
            'title' => 'Cambio de horario',
            'message' => "Cambio de horario para {$data['subject_name']} - Grupo {$data['group_name']}. De {$oldDay} {$oldTime} a {$newDay} {$newTime}",
            'data' => $data,
        ]);
    }

    /**
     * Crear notificación de comunicación directa (manual)
     */
    public function createDirectMessage(
        User $recipient,
        User $sender,
        string $subject,
        string $message,
        string $greeting = 'Estimado profesor'
    ): Notification {
        $data = [
            'sender_id' => $sender->id,
            'sender_name' => $sender->name,
            'greeting' => $greeting,
        ];

        return Notification::create([
            'user_id' => $recipient->id,
            'notification_type' => 'direct_message',
            'priority' => 'info',
            'is_automatic' => false,
            'title' => $subject,
            'message' => "{$greeting},\n\n{$message}\n\nAtentamente,\n{$sender->name}",
            'data' => $data,
        ]);
    }

    /**
     * Crear notificación de reserva aprobada
     */
    public function createReservationApprovedNotification(
        User $teacher,
        $reservationId,
        $classroomNumber,
        $date,
        $time,
        User $approvedBy
    ): Notification {
        $data = [
            'reservation_id' => $reservationId,
            'classroom_number' => $classroomNumber,
            'date' => $date,
            'time' => $time,
            'approved_by' => $approvedBy->name,
        ];

        return Notification::create([
            'user_id' => $teacher->id,
            'notification_type' => 'reservation_approved',
            'priority' => 'info',
            'is_automatic' => true,
            'title' => 'Solicitud aprobada',
            'message' => "Tu reserva del Aula {$classroomNumber} para el {$date} ({$time}) ha sido aprobada por {$approvedBy->name}",
            'data' => $data,
        ]);
    }

    /**
     * Crear notificación de reserva rechazada
     */
    public function createReservationRejectedNotification(
        User $teacher,
        $reservationId,
        $classroomNumber,
        $date,
        $time,
        string $reason
    ): Notification {
        $data = [
            'reservation_id' => $reservationId,
            'classroom_number' => $classroomNumber,
            'date' => $date,
            'time' => $time,
            'rejection_reason' => $reason,
        ];

        return Notification::create([
            'user_id' => $teacher->id,
            'notification_type' => 'reservation_rejected',
            'priority' => 'important',
            'is_automatic' => true,
            'title' => 'Solicitud rechazada',
            'message' => "Tu reserva del Aula {$classroomNumber} para el {$date} ha sido rechazada. Motivo: {$reason}",
            'data' => $data,
        ]);
    }

    /**
     * Crear notificación de permiso de reserva
     */
    public function createReservationPermissionNotification(
        User $admin,
        User $teacher,
        $reservationId,
        $classroomNumber,
        $date,
        $time
    ): Notification {
        $data = [
            'reservation_id' => $reservationId,
            'teacher_id' => $teacher->id,
            'teacher_name' => $teacher->name,
            'classroom_number' => $classroomNumber,
            'date' => $date,
            'time' => $time,
        ];

        return Notification::create([
            'user_id' => $admin->id,
            'notification_type' => 'reservation_permission',
            'priority' => 'important',
            'is_automatic' => true,
            'title' => 'Nueva solicitud de permiso',
            'message' => "El docente {$teacher->name} solicita reservar el Aula {$classroomNumber} para el {$date} ({$time})",
            'data' => $data,
        ]);
    }

    /**
     * Marcar todas las notificaciones de un usuario como leídas
     */
    public function markAllAsRead(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Obtener contador de notificaciones no leídas
     */
    public function getUnreadCount(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Limpiar notificaciones antiguas (más de 30 días leídas)
     */
    public function cleanOldNotifications(): int
    {
        return Notification::whereNotNull('read_at')
            ->where('read_at', '<', now()->subDays(30))
            ->delete();
    }
}
