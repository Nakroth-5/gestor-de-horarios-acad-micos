<?php

use App\Http\Controllers\Api\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::post('/attendance/mark', [AttendanceController::class, 'markAttendance'])->name('api.attendance.mark');
