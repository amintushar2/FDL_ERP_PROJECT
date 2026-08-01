<?php

use App\Http\Controllers\Payroll\AdvanceLoanController;
use Illuminate\Support\Facades\Route;

// ── Blade Views ───────────────────────────────────────────────────────
Route::get('/payroll/advance-loan', [AdvanceLoanController::class, 'index'])
    ->name('payroll.advance-loan.index')
    ->middleware('auth');

Route::get('/payroll/advance-loan/print/{loanAppNo}', [AdvanceLoanController::class, 'print'])
    ->name('payroll.advance-loan.print')
    ->middleware('auth')
    ->where('loanAppNo', '.*');

// ── API / AJAX (called by JavaScript fetch) ───────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/api/payroll/employees',                     [AdvanceLoanController::class, 'employees']);
    Route::get('/api/payroll/advance-loan/out-amount',       [AdvanceLoanController::class, 'outAmount']);
    Route::get('/api/payroll/advance-loan/previous-balance', [AdvanceLoanController::class, 'previousBalance']);
    Route::get('/api/payroll/advance-loan',                  [AdvanceLoanController::class, 'show']);
    Route::post('/api/payroll/advance-loan',                 [AdvanceLoanController::class, 'store']);
    Route::post('/api/payroll/advance-loan/schedule',        [AdvanceLoanController::class, 'generateSchedule']);
    Route::post('/api/payroll/advance-loan/out-payment',     [AdvanceLoanController::class, 'processOutPayment']);
    Route::post('/api/payroll/advance-loan/reschedule',      [AdvanceLoanController::class, 'reschedule']);
    Route::put('/api/payroll/advance-loan/{id}',             [AdvanceLoanController::class, 'update'])->where('id', '.*');
    Route::delete('/api/payroll/advance-loan/{id}/due',         [AdvanceLoanController::class, 'destroyDue'])->where('id', '.*');
    Route::delete('/api/payroll/advance-loan/{id}',          [AdvanceLoanController::class, 'destroy'])->where('id', '.*');

});

