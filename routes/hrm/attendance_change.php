<?php
 use App\Http\Controllers\HrmModule\AttendanceChangeController; 
// ── Paste inside your auth middleware group in routes/web.php ────────────────
//
// use App\Http\Controllers\HRM\AttendanceChangeController;

Route::prefix('hrm/attendance-change')->name('hrm.att-change.')->middleware(['auth'])->group(function () {
     Route::get('/',           [AttendanceChangeController::class, 'index']       )->name('index');
    Route::get('/companies',  [AttendanceChangeController::class, 'getCompanies'])->name('companies');
    Route::get('/search',     [AttendanceChangeController::class, 'search']      )->name('search');
    Route::post('/save',      [AttendanceChangeController::class, 'save']        )->name('save');
    Route::post('/insert',    [AttendanceChangeController::class, 'insert']      )->name('insert');
    Route::post('/delete',    [AttendanceChangeController::class, 'delete']      )->name('delete');
    Route::post('/set-out3',  [AttendanceChangeController::class, 'setOutTime3'] )->name('set-out3');
});
