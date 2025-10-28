<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\AcademicProcesses\SubjectManager;
use App\Livewire\AcademicLogistics\ScheduleBlockManager;
use App\Livewire\AcademicLogistics\ClassroomManager;
use App\Livewire\AcademicLogistics\InfrastructureManager;
use App\Livewire\SecurityAccess\AuditLogManager;
use App\Livewire\SecurityAccess\RoleManager;
use App\Livewire\SecurityAccess\UserManager;
use Illuminate\Support\Facades\Route;

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
    Route::get('/schedule-block', ScheduleBlockManager::class)->name('schedule-block.index');
    Route::get('/academic-logistics/infrastructure', InfrastructureManager::class)->name('infrastructure.index');
    Route::get('/academic-logistics/classroom', ClassroomManager::class)->name('classroom.index');
    Route::get('/security-access/auditLog', AuditLogManager::class)->name('auditLog.index');
});

require __DIR__.'/auth.php';
