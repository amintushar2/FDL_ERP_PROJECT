<?php
// ── Add inside Route::middleware(['auth'])->group(...) in routes/web.php ──

use App\Http\Controllers\Admin\SchemaBackupController;

Route::prefix('admin/schema-backup')->name('admin.schema-backup.')->group(function () {
    Route::get('/',              [SchemaBackupController::class, 'index'])      ->name('index');
    Route::post('/run',          [SchemaBackupController::class, 'run'])        ->name('run');
    Route::get('/ftp-list',      [SchemaBackupController::class, 'ftpList'])   ->name('ftp-list');
    Route::get('/ftp-test',      [SchemaBackupController::class, 'ftpTest'])   ->name('ftp-test');
    Route::get('/ftp-download',  [SchemaBackupController::class, 'ftpDownload'])->name('ftp-download');
    Route::delete('/ftp-delete', [SchemaBackupController::class, 'ftpDelete']) ->name('ftp-delete');
});
