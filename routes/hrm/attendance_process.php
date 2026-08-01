<?php
/* ══════════════════════════════════════════════════════════════════
   ROUTES  →  paste into routes/web.php
   ══════════════════════════════════════════════════════════════════ */

use App\Http\Controllers\HrmModule\AttendanceController;
use Illuminate\Support\Facades\Route;

// ── Blade View ────────────────────────────────────────────────────
Route::get('/hrm/attendance/process', [AttendanceController::class, 'index'])
    ->name('hrm.attendance.zkteco')
    ->middleware('auth');

// ── API Routes ────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

      Route::get   ('/api/attendance/data',               [AttendanceController::class, 'getData']);
    Route::get   ('/api/attendance/download',           [AttendanceController::class, 'downloadData']);
    Route::get   ('/api/attendance/delete-old/count',   [AttendanceController::class, 'countOldData']);
    Route::post  ('/api/attendance/fetch',              [AttendanceController::class, 'fetchFromDevice']);
    Route::post  ('/api/attendance/process',            [AttendanceController::class, 'processAttendance']);
    Route::post  ('/api/attendance/ping',               [AttendanceController::class, 'pingDevice']);
    Route::delete('/api/attendance/delete-old',         [AttendanceController::class, 'deleteOldData']);
    Route::delete('/api/attendance/delete-device',      [AttendanceController::class, 'deleteFromDevice']);
    Route::get   ('/api/attendance/devices',            [AttendanceController::class, 'getDevices']);
    Route::post  ('/api/attendance/devices',            [AttendanceController::class, 'storeDevice']);
    Route::put   ('/api/attendance/devices/{id}',       [AttendanceController::class, 'updateDevice']);
    Route::delete('/api/attendance/devices/{id}',       [AttendanceController::class, 'destroyDevice']);
});
