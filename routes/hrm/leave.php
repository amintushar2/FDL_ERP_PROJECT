<?php

use App\Http\Controllers\HrmModule\LeaveEntryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('hrm/leave-entry')->name('leave.')->group(function () {
Route::get('/',                                  [LeaveEntryController::class, 'index'])->name('index');
    Route::get('/create',                            [LeaveEntryController::class, 'create'])->name('create');
    Route::post('/',                                 [LeaveEntryController::class, 'store'])->name('store');
    Route::get('/{empno}/{year}/{company_id}',       [LeaveEntryController::class, 'show'])->name('show');
    Route::get('/{empno}/{year}/{company_id}/edit',  [LeaveEntryController::class, 'edit'])->name('edit');
    Route::post('/{empno}/{year}/{company_id}',      [LeaveEntryController::class, 'update'])->name('update');   // POST with _method=PUT
    Route::delete('/{empno}/{year}/{company_id}',    [LeaveEntryController::class, 'destroy'])->name('destroy');

    // Leave Slip — keyed by empno + leave_id + lv_from (natural composite key)
    Route::get('/slip/{empno}/{leave_id}/{lv_from}', [LeaveEntryController::class, 'leaveSlip'])->name('slip');

    // AJAX
    Route::delete('/detail/{empno}/{leave_id}/{lv_from}', [LeaveEntryController::class, 'destroyDetail'])->name('detail.destroy');
    Route::get('/ajax/employee',                     [LeaveEntryController::class, 'getEmployee'])->name('ajax.employee');
    Route::get('/ajax/balances',                     [LeaveEntryController::class, 'getBalances'])->name('ajax.balances');
    Route::post('/ajax/validate-leave-day',          [LeaveEntryController::class, 'validateLeaveDay'])->name('ajax.validateLeaveDay');
    Route::get('/ajax/prev-year-details',            [LeaveEntryController::class, 'getPreviousYearDetails'])->name('ajax.prevYearDetails');
});
