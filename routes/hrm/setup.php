<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HrmSetup\DepartmentController;
use App\Http\Controllers\HrmSetup\SectionController;
use App\Http\Controllers\HrmSetup\DesignationController;
use App\Http\Controllers\HrmSetup\FloorController;
use App\Http\Controllers\HrmSetup\LineController;
use App\Http\Controllers\HrmSetup\ShiftController;
use App\Http\Controllers\HrmSetup\ReligionController;
use App\Http\Controllers\HrmSetup\EmpTypeController;
use App\Http\Controllers\HrmSetup\EntrySystemController;

Route::prefix('hrm/setup')->name('setup.')->middleware(['auth'])->group(function () {
    Route::resource('department',   DepartmentController::class)->names('department');
    Route::resource('section',      SectionController::class)->names('section');
    Route::resource('designation',  DesignationController::class)->names('designation');
    Route::resource('floor',        FloorController::class)->names('floor');
    Route::resource('line',         LineController::class)->names('line');
    Route::resource('shift',        ShiftController::class)->names('shift');
    Route::resource('religion',     ReligionController::class)->names('religion');
    Route::resource('emp-type',     EmpTypeController::class)->names('emp-type');
    Route::resource('entry-system', EntrySystemController::class)->names('entry-system');
});
