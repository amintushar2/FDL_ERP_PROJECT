<?php

// Add inside routes/web.php (group with your existing 'auth' + HRM middleware as needed)

use App\Http\Controllers\HrmModule\DailyOfficeFoodController;

Route::prefix('hrm/daily-office-food')->name('hrm.daily-office-food.')->group(function () {
    Route::get('/', [DailyOfficeFoodController::class, 'index'])->name('index');
    Route::post('/load', [DailyOfficeFoodController::class, 'load'])->name('load');
    Route::post('/save', [DailyOfficeFoodController::class, 'save'])->name('save');
    Route::post('/delete-preview', [DailyOfficeFoodController::class, 'deletePreview'])->name('delete-preview');
    Route::delete('/', [DailyOfficeFoodController::class, 'destroy'])->name('destroy');
});
