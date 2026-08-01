<?php
// ─────────────────────────────────────────────────────────────
//  Add these lines inside your Route::middleware(['auth'])->group(...)
//  in routes/web.php
// ─────────────────────────────────────────────────────────────

use App\Http\Controllers\Payroll\FestivalBonusController;

Route::prefix('payroll/festival-bonus')->name('payroll.festival-bonus.')->group(function () {
        Route::get('/',               [FestivalBonusController::class, 'index'])      ->name('index');
    Route::post('/calculate',     [FestivalBonusController::class, 'calculate'])  ->name('calculate');
    Route::get('/last-batch',     [FestivalBonusController::class, 'lastBatch'])  ->name('last-batch');
    Route::delete('/delete-last', [FestivalBonusController::class, 'deleteLast'])->name('delete-last');
    Route::get('/preview',        [FestivalBonusController::class, 'preview'])    ->name('preview');
});
