<?php

// ── Paste inside your auth middleware group in routes/web.php ────────────────
//
 use App\Http\Controllers\Payroll\IncrementEntryController;

Route::prefix('payroll/increment-entry')->name('payroll.increment.')->middleware(['auth'])->group(function () {
    Route::get('/',               [IncrementEntryController::class, 'index']          )->name('index');
    Route::get('/employees',      [IncrementEntryController::class, 'getEmployees']   )->name('employees');
    Route::get('/emp-info',       [IncrementEntryController::class, 'getEmployeeInfo'])->name('emp-info');
    Route::get('/history',        [IncrementEntryController::class, 'getHistory']     )->name('history');
    Route::post('/calculate',     [IncrementEntryController::class, 'calculate']      )->name('calculate');
    Route::get('/designations',   [IncrementEntryController::class, 'getDesignations'])->name('designations');
    Route::get('/grades',         [IncrementEntryController::class, 'getGrades']      )->name('grades');
    Route::post('/save',          [IncrementEntryController::class, 'save']           )->name('save');
    Route::post('/delete',        [IncrementEntryController::class, 'delete']         )->name('delete');
});
