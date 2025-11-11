<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceScanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserImportController;
use App\Livewire\AcademicLogistics\ManualScheduleAssignment;
use App\Livewire\AcademicLogistics\Attendance\AttendanceQrManager;
use App\Livewire\AcademicProcesses\GroupManager;
use App\Livewire\AcademicProcesses\SubjectManager;
use App\Livewire\AcademicProcesses\AcademicPeriodManager;
use App\Livewire\AcademicProcesses\TeacherScheduleView;
use App\Livewire\AcademicLogistics\ScheduleBlockManager;
use App\Livewire\AcademicLogistics\ClassroomManager;
use App\Livewire\AcademicLogistics\InfrastructureManager;
use App\Livewire\AcademicLogistics\SpecialReservationManager;
use App\Livewire\AcademicManagement\UniversityCareerManager;
use App\Livewire\SecurityAccess\AuditLogManager;
use App\Livewire\SecurityAccess\RoleManager;
use App\Livewire\SecurityAccess\UserManager;
use App\Livewire\SecurityAccess\UserImport;
use Illuminate\Support\Facades\Route;
use App\Livewire\AcademicProcesses\TeacherSubjectManager;

Route::get('/', function () {
    return redirect('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/user', UserManager::class)->name('user.index');
    Route::get('/role', RoleManager::class)->name('role.index');
    Route::get('/subject', SubjectManager::class)->name('subject.index');
    Route::get('/teacher-subject', TeacherSubjectManager::class)->name('teacher-subject.index');

    Route::get('/academic-logistics/infrastructure', InfrastructureManager::class)->name('infrastructure.index');
    Route::get('/academic-logistics/classroom', ClassroomManager::class)->name('classroom.index');
    Route::get('/academic-logistics/schedule-block', ScheduleBlockManager::class)->name('schedule-block.index');
    Route::get('/academic-logistics/manual-schedule-assignment', ManualScheduleAssignment::class)->name('manual-schedule-assignment.index');
    Route::get('/academic-logistics/attendance', AttendanceQrManager::class)->name('attendance.index');
    Route::get('/academic-logistics/special-reservations', SpecialReservationManager::class)->name('special-reservations.index');

    Route::get('/academic-process/academic-periods', AcademicPeriodManager::class)->name('academic-periods.index');
    Route::get('/my-schedule', TeacherScheduleView::class)->name('my-schedule.index');
    
    Route::get('/academic-management/university-careers', UniversityCareerManager::class)->name('university-careers.index');
    
    Route::get('/security-access/auditLog', AuditLogManager::class)->name('auditLog.index');
    Route::get('/security-access/user-import', UserImport::class)->name('user-import.index');
    Route::get('/security-access/user-import/template', [UserImportController::class, 'downloadTemplate'])->name('user-import.template');
    Route::get('/academic-process/group', GroupManager::class)->name('group.index');

    // Rutas de Reportes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/weekly-schedules', [ReportController::class, 'weeklySchedules'])->name('reports.weekly-schedules');
    Route::get('/reports/attendance', [ReportController::class, 'attendanceReport'])->name('reports.attendance');
    Route::get('/reports/available-classrooms', [ReportController::class, 'availableClassrooms'])->name('reports.available-classrooms');
    
    // Exportación a Excel (CSV)
    Route::get('/reports/weekly-schedules/export', [ReportController::class, 'exportWeeklySchedules'])->name('reports.weekly-schedules.export');
    Route::get('/reports/attendance/export', [ReportController::class, 'exportAttendance'])->name('reports.attendance.export');
    Route::get('/reports/available-classrooms/export', [ReportController::class, 'exportAvailableClassrooms'])->name('reports.available-classrooms.export');
});

// Ruta pública para escanear QR (requiere autenticación pero se maneja en el controlador)
Route::get('/asistencia/marcar/{assignment}', [AttendanceScanController::class, 'scan'])->name('attendance.scan');

require __DIR__.'/auth.php';
