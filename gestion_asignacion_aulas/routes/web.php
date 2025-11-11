<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceScanController;
use App\Livewire\AcademicLogistics\ManualScheduleAssignment;
use App\Livewire\AcademicLogistics\Attendance\AttendanceQrManager;
use App\Livewire\AcademicProcesses\GroupManager;
use App\Livewire\AcademicProcesses\SubjectManager;
use App\Livewire\AcademicLogistics\ScheduleBlockManager;
use App\Livewire\AcademicLogistics\ClassroomManager;
use App\Livewire\AcademicLogistics\InfrastructureManager;
use App\Livewire\SecurityAccess\AuditLogManager;
use App\Livewire\SecurityAccess\RoleManager;
use App\Livewire\SecurityAccess\UserManager;
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

    Route::get('/security-access/auditLog', AuditLogManager::class)->name('auditLog.index');
    Route::get('/academic-process/group', GroupManager::class)->name('group.index');
});

// Ruta pública para escanear QR (requiere autenticación pero se maneja en el controlador)
Route::get('/asistencia/marcar/{assignment}', [AttendanceScanController::class, 'scan'])->name('attendance.scan');

require __DIR__.'/auth.php';
