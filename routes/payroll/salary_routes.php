<?php
/*
|──────────────────────────────────────────────────────────────────
|  SALARY MODULE ROUTES  (Payroll)
|  File: routes/payroll/salary.php
|
|  Add to web.php:
|      require base_path('routes/payroll/salary.php');
|
|  Uses alias to avoid conflict with existing SalaryController
|──────────────────────────────────────────────────────────────────
*/

use App\Http\Controllers\Payroll\SalaryController as PaySalaryController;
use Illuminate\Support\Facades\Route;

// ── Blade View ────────────────────────────────────────────────────
Route::get('/payroll/salary', [PaySalaryController::class, 'index'])
    ->name('payroll.salary.index')
    ->middleware('auth');

// ── API Routes ────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // ── EMP_PAYMENT table (main working table) ─────────────────────
    // List EMP_PAYMENT entries for a company
    Route::get('/api/salary/entries',              [PaySalaryController::class, 'getEntries']);

    // Single EMP_PAYMENT entry (for edit form)
    Route::get('/api/salary/entry',                [PaySalaryController::class, 'getEntry']);

    // Employee info auto-fill (WHEN-VALIDATE-ITEM on EMPNO)
    Route::get('/api/salary/emp-info',             [PaySalaryController::class, 'getEmpInfo']);

    // Salary parameters (STAMP, HR%, MR_AMT, CONVANCE, FOOD)
    Route::get('/api/salary/params',               [PaySalaryController::class, 'getSalaryParams']);

    // PB_GROSS (Gross Entry button): load all active employees → insert into EMP_PAYMENT
    Route::post('/api/salary/gross-entry',         [PaySalaryController::class, 'grossEntry']);

    // Create single EMP_PAYMENT entry (CTRL.PB_SAVE)
    Route::post('/api/salary/entry',               [PaySalaryController::class, 'storeEntry']);

    // Update EMP_PAYMENT entry
    Route::put('/api/salary/entry/{empNo}',        [PaySalaryController::class, 'updateEntry'])
        ->where('empNo', '.*');

    // Delete EMP_PAYMENT single entry
    Route::delete('/api/salary/entry/{empNo}',     [PaySalaryController::class, 'destroyEntry'])
        ->where('empNo', '.*');

    // ── SALARY_PAYMENT_INFO table (processed results) ──────────────
    // Process salary → SAL_MAIN.SAL_CHK_IF_SALARY_CALC(date1, date2, company_id)
    Route::post('/api/salary/process',             [PaySalaryController::class, 'processSalary']);

    // Distinct payment dates for delete LOV dropdown
    // SELECT DISTINCT PAYMENT_DATE FROM SALARY_PAYMENT_INFO WHERE COMPANY_ID = :b1
    Route::get('/api/salary/delete/dates',         [PaySalaryController::class, 'getDeleteDates']);

    // Count before delete
    Route::get('/api/salary/delete/count',         [PaySalaryController::class, 'countSalary']);

    // Delete salary period from SALARY_PAYMENT_INFO
    Route::delete('/api/salary/delete',            [PaySalaryController::class, 'deleteSalary']);
});
