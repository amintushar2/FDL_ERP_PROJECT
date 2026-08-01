<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EmpControllers;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpEntListController;
use App\Http\Controllers\LoanController;
use App\Http\controllers\HrmSetupController;
use App\Http\controllers\Reports\ReportCenterController;
use App\Http\controllers\HrmDashboardController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SalProcessController;
use App\Http\Controllers\LovController;
use App\Http\Controllers\TempEmpController;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\UserGroupController;
use App\Http\Controllers\Admin\GroupMenuController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserMenuPermissionController;
use App\Http\Controllers\Admin\UserCompanyPermissionController;
use App\Http\Controllers\TempEmpPersonalController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Setup\DepartmentController;
use App\Http\Controllers\Setup\SectionController;
use App\Http\Controllers\Setup\DesignationController;
use App\Http\Controllers\Setup\FloorController;
use App\Http\Controllers\Setup\LineController;
use App\Http\Controllers\Setup\ShiftController;
use App\Http\Controllers\Setup\ReligionController;
use App\Http\Controllers\Setup\EmpTypeController;
use App\Http\Controllers\Setup\EntrySystemController;
use App\Http\Controllers\HrmModule\LeaveEntryController;








Route::get('/check-login', function () {
    return [
        'auth_check' => auth()->check(),
        'user' => auth()->user()
    ];
});
Route::prefix('api')->middleware(['auth'])->group(function () {
    
    // ──── PERSONAL INFORMATION ────
    Route::post('/saveEmpPersonal', [EmpControllers::class, 'saveEmpPersonal']);
    
    // ──── OFFICIAL INFORMATION ────
    Route::post('/saveEmpOfficial', [EmpControllers::class, 'saveEmpOfficial']);
    
    // ──── LOCATION/ADDRESS INFORMATION ────
    Route::post('/saveEmpLocation', [EmpControllers::class, 'saveEmpLocation']);
    
    // ──── EDUCATION/QUALIFICATION ────
    Route::post('/saveEmpQualification', [EmpControllers::class, 'saveEmpQualification'])->name('saveEmpQualification');
    Route::put('/updateEmpQualification/{id}', [EmpControllers::class, 'updateEmpQualification'])->name('updateEmpQualification');
    Route::delete('/deleteEmpQualification/{id}', [EmpControllers::class, 'deleteEmpQualification'])->name('deleteEmpQualification');
    Route::get('/getEmpQualifications/{empno}', [EmpControllers::class, 'getEmpQualifications'])->name('getEmpQualifications');
    Route::get('/getPassedExams', [EmpControllers::class, 'getPassedExams'])->name('getPassedExams');
    
    // ──── SHORT COURSES ────
    Route::post('/saveEmpShortCourse', [EmpControllers::class, 'saveEmpShortCourse']);
    Route::get('/getEmpShortCourses/{empno}', [EmpControllers::class, 'getEmpShortCourses'])->name('getEmpShortCourses');
    
    // ──── FAMILY INFORMATION ────
    Route::post('/saveEmpFamily', [EmpControllers::class, 'saveEmpFamily']);
    Route::put('/updateEmpFamily', [EmpControllers::class, 'updateEmpFamily'])->name('updateEmpFamily');
    Route::delete('/deleteEmpFamily/', [EmpControllers::class, 'deleteEmpFamily'])->name('deleteEmpFamily');
    Route::get('/getEmpFamily/{empno}', [EmpControllers::class, 'getEmpFamily'])->name('getEmpFamily');
    
    // ──── JOB HISTORY ────
    Route::post('/saveEmpHistory', [EmpControllers::class, 'saveEmpHistory']);
    Route::get('/getEmpHistory/{empno}', [EmpControllers::class, 'getEmpHistory']);
    
    // ──── TRAINING ────
    Route::post('/saveEmpTraining', [EmpControllers::class, 'saveEmpTraining']);
    Route::get('/getEmpTrainings/{empno}', [EmpControllers::class, 'getEmpTrainings']);
    
    // ──── WORK EXPERIENCE ────
    Route::post('/saveEmpWorkExp', [EmpControllers::class, 'saveEmpWorkExp']);
    Route::put('/updateEmpWorkExp/{id}', [EmpControllers::class, 'updateEmpWorkExp']);
    Route::delete('/deleteEmpWorkExp/{id}', [EmpControllers::class, 'deleteEmpWorkExp']);
    Route::get('/getEmpWorkExperience/{empno}', [EmpControllers::class, 'getEmpWorkExperience']);
    
    // ──── GET ALL EMPLOYEE DETAILS ────
    Route::get('/getEmpDetails', [EmpControllers::class, 'getEmpDetails']);
    
    // ──── DELETE RECORDS ────
    Route::delete('/deleteEmpRecord/{id}', [EmpControllers::class, 'deleteEmpRecord']);


    Route::post('/saveEmpLocationBangla', [EmpControllers::class, 'saveEmpLocationBangla']);
    
    /**
     * Get Bangla Location
     * GET: /api/getEmpLocationBangla/{empno}
     */
    Route::get('/getEmpLocationBangla/{empno}', [EmpControllers::class, 'getEmpLocationBangla']);
    
    /**
     * Update Bangla Location
     * PUT: /api/updateEmpLocationBangla/{empno}
     */
    Route::put('/updateEmpLocationBangla/{empno}', [EmpControllers::class, 'updateEmpLocationBangla']);
    
    /**
     * Delete Bangla Location
     * DELETE: /api/deleteEmpLocationBangla/{empno}
     */
    Route::delete('/deleteEmpLocationBangla/{empno}', [EmpControllers::class, 'deleteEmpLocationBangla']);
    
    // ══════════════════════════════════════════════════════════════════
    // COMBINED - GET BOTH ENGLISH AND BANGLA
    // ══════════════════════════════════════════════════════════════════
    
    /**
     * Get Both English and Bangla Location
     * GET: /api/getEmpLocationCombined/{empno}
     */
    Route::get('/getEmpLocationCombined/{empno}', [EmpControllers::class, 'getEmpLocationCombined']);
});





Route::get('/check-login', function () {
    return [
        'auth_check' => auth()->check(),
        'user' => auth()->user()
    ];
});


Route::middleware(['web'])->group(function () {
Route::get('/',[LoginController::class,'login'])->name('login');
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('auth/check', [LoginController::class, 'check'])->name('auth.check');

   // Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');

});


 
Route::middleware(['auth'])->name('hrm.')->group(function () {
    Route::get('/dashboard', [HrmDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/live-data', [LoginController::class, 'liveData'])->name('dashboard.liveData');

});
 



Route::get('/auth/logout',[LoginController::class, 'logout'])->name('auth.logout');




//HRM Setup routes are defined in setup.php to keep web.php cleaner.

Route::middleware(['web','auth'])->group(function () {

    // Employee pages
    Route::get('/hrm/emplist',          [EmpControllers::class,'empList'])->name('emplist');
    Route::get('/hrm/empentry',         [EmpControllers::class,'empentry'])->name('empnewentry');
    Route::get('/hrm/empedit/{empno}',  [EmpControllers::class,'empEdit'])->name('empedit');
    Route::get('/hrm/empedite/{empno}',  [EmpControllers::class,'empEditEntry'])->name('empedite');

    // Lazy AJAX tab loaders
    Route::get('/hrm/tab/official/{empno}',    [EmpControllers::class,'tabOfficial'])->name('tab.official');
    Route::get('/hrm/tab/location/{empno}',    [EmpControllers::class,'tabLocation'])->name('tab.location');
    Route::get('/hrm/tab/education/{empno}',   [EmpControllers::class,'tabEducation'])->name('tab.education');
    Route::get('/hrm/tab/shortcourse/{empno}', [EmpControllers::class,'tabShortCourse'])->name('tab.shortcourse');
    Route::get('/hrm/tab/training/{empno}',    [EmpControllers::class,'tabTraining'])->name('tab.training');
    Route::get('/hrm/tab/experience/{empno}',  [EmpControllers::class,'tabExperience'])->name('tab.experience');
    Route::get('/hrm/tab/nominee/{empno}',     [EmpControllers::class,'tabNominee'])->name('tab.nominee');
    Route::get('/hrm/tab/jobhistory/{empno}',  [EmpControllers::class,'tabJobHistory'])->name('tab.jobhistory');

    // Autocomplete
    Route::get('/hrm/empsearch',        [EmpControllers::class,'empsearch']);
    Route::post('/hrm/empSearchExist',  [EmpControllers::class,'empSearchExist']);


    Route::get('/hrm/emplist',        [EmpControllers::class, 'empList'])->name('emplist');
 
    // AJAX search endpoint — called by the filter bar
    Route::get('/hrm/emplist/search', [EmpControllers::class, 'empListSearch'])->name('emplist.search');
    // ── LOV AJAX endpoints (all return {results:[{id,text}]}) ──
    Route::get('/lov/dept',        [LovController::class,'dept']);
    Route::get('/lov/section',     [LovController::class,'section']);
    Route::get('/lov/floor',       [LovController::class,'floor']);
    Route::get('/lov/line',        [LovController::class,'line']);
    Route::get('/lov/designation', [LovController::class,'designation']);
    Route::get('/lov/grade',       [LovController::class,'grade']);
    Route::get('/lov/shift',       [LovController::class,'shift']);
    Route::get('/lov/calendar',    [LovController::class,'calendar']);
    Route::get('/lov/weeklyoff',   [LovController::class,'weeklyoff']);
    Route::get('/lov/bank',        [LovController::class,'bank']);
    Route::get('/lov/leavecategory',    [LovController::class,'leavecat']);
    Route::get('/lov/allwcat',     [LovController::class,'allwcat']);
    Route::get('/lov/thana',       [LovController::class,'thana']);
    Route::get('/lov/district',    [LovController::class,'district']);
    Route::get('/lov/yesno',       [LovController::class,'yesno']);
    Route::get('/lov/workEnt',     [LovController::class,'workEnt']);

    // Leave
    Route::get('/hrm/getLeaveDetails/{empno}/{year}',     [EmpControllers::class,'getLeaveDetails']);
    Route::get('/hrm/getLeavePrebal/{empno}/{year}/{lv}', [EmpControllers::class,'getLeavePrebal']);
    Route::get('/hrm/getLeavBal/{lv}',                    [EmpControllers::class,'getLeavBal']);
    Route::post('/hrm/leaveEntryIns',                     [EmpControllers::class,'leaveEntryIns']);
    Route::post('/hrm/leaveEntryDet',                     [EmpControllers::class,'leaveEntryDet']);
    Route::delete('/hrm/deleteLeave/{empno}/{year}/{sl}', [EmpControllers::class,'deleteLeave']);
});



//report
// Route::get('/hrm/report',[ReportController::class, 'hrmreport'])->name('report.hrm')->middleware(['auth']);
// Route::get('getReportPera/{id}',[ReportController::class, 'getReportId'])->name('report.hrm');
// Route::get('getReportFile/{id}',[ReportController::class, 'getReportFile'])->name('report.hrm');
// Route::get('getReportName/{id}',[ReportController::class, 'getReportName'])->name('report.hrm');
// Route::get('hrm/pdfReport',[ReportController::class, 'pdfview']);




Route::get('/common/gatepass',[GpController::class, 'gatepass'])->name('common.gatepass')->middleware(['auth']);
//Route::get('/file',[GpController::class, 'getFile'])->name('getFile');



//emp_controller 
// Route::get('/empnewentryfind',[EmpControllers::class,'empnewentryfind']);
// Route::get('/empsearch',[EmpControllers::class,'empsearch'])->middleware(['auth']);

// Route::get('/index',[EmpControllers::class,'index']);
// Route::post('/emppersonalsave',[EmpControllers::class,'employeePersonalInsert'])->name('employeePersonalInsert')->middleware(['auth']);
// Route::post('/empoffcsave',[EmpControllers::class,'employeeOfficialInsert'])->name('employeeOfficalInsert')->middleware(['auth']);
// Route::post('/empaddcsave',[EmpControllers::class,'empaddressSave'])->name('empaddressSave')->middleware(['auth']);
// Route::post('/empEducsave',[EmpControllers::class,'empeduSave'])->name('empEducsave')->middleware(['auth']);
// Route::post('/empShortSave',[EmpControllers::class,'empShortSave'])->name('empEducsave')->middleware(['auth']);
// Route::post('/empNomineeSave',[EmpControllers::class,'empNomineeSave'])->name('empEducsave')->middleware(['auth']);
// Route::post('/empHistory',[EmpControllers::class,'empHistory'])->name('empHistory')->middleware(['auth']);
// Route::post('/empTraining',[EmpControllers::class,'empTraining'])->middleware(['auth']);
// Route::post('/empExp',[EmpControllers::class,'empExp'])->middleware(['auth']);
// Route::get('/empPerUpdate',[EmpControllers::class,'employeePersonalUpdate'])->middleware(['auth']);
// Route::get('/leave',[EmpControllers::class,'leaveentry'])->middleware(['auth']);
// Route::get('/getLeaveDetails/{empno}/{year}',[EmpControllers::class,'getLeaveDetails'])->middleware(['auth']);
// Route::get('/getPrebal/{empno}/{year}/{lv}',[EmpControllers::class,'getLeavePrebal'])->middleware(['auth']);
// Route::get('/getLeavBal/{lv}',[EmpControllers::class,'getLeavBal'])->middleware(['auth']);
// Route::get('/LeaveEntry',[EmpControllers::class,'leaveEntryIns'])->middleware(['auth']);
// Route::get('/LeaveEntryDet',[EmpControllers::class,'leaveEntryDet'])->middleware(['auth']);
// Route::get('/deleteLeave/{empno}/{year}/{lvsl}',[EmpControllers::class,'deleteLeave'])->middleware(['auth']);
// Route::get('/empSearchExist',[EmpControllers::class,'empSearchExist'])->middleware(['auth']);



//emp List Route

// Route::get('/getDept/{id}',[EmpEntListController::class,'deptList'])->middleware(['auth']);
// Route::get('/floorList/{id}',[EmpEntListController::class,'floorList'])->middleware(['auth']);
// Route::get('/city',[EmpEntListController::class,'city'])->middleware(['auth']);



// Route::get('/getEmp',[EmpEntListController::class,'getEmp'])->middleware(['auth']);
// Route::get('/demo',[EmpEntListController::class,'demo'])->middleware(['auth']);
// Route::get('/getEdu/{id}',[EmpEntListController::class,'getEdu'])->middleware(['auth']);
// Route::get('/getCourse/{id}',[EmpEntListController::class,'getCourse'])->middleware(['auth']);
// Route::get('/getNome/{id}',[EmpEntListController::class,'getNome'])->middleware(['auth']);
// Route::get('/getJob/{id}',[EmpEntListController::class,'getJob'])->middleware(['auth']);
// Route::get('/getTrain/{id}',[EmpEntListController::class,'getTrain'])->middleware(['auth']);
// Route::get('/empExper/{id}',[EmpEntListController::class,'empExper'])->middleware(['auth']);


// Route::get('/loan',[LoanController::class,'loan'])->middleware(['auth']);
// Route::get('/loandt',[LoanController::class,'loandt'])->middleware(['auth']);




// Route::post('/loansave',[LoanController::class,'storeer'])->middleware(['auth']);
// Route::get('/loandtsave',[LoanController::class,'storeerdetails'])->middleware(['auth']);


// Route::get('/getemp/{comid}',[LoanController::class,'getEmpNO'])->middleware(['auth']);
// Route::get('/getempdet',[LoanController::class,'getEMPdetails'])->middleware(['auth']);
// Route::get('/getinEmp',[EmpControllers::class,'getEmpDet'])->middleware(['auth']);
// Route::get('/getprevgross',[EmpControllers::class,'getPrevGross'])->middleware(['auth']);


//ADMIN LIST

// Route::get('/menudetails',[AdminController::class,'getAllMenu'])->middleware(['auth']);
// Route::get('/getEMPDT',[EmpEntListController::class,'getEMPDT'])->middleware(['auth']);

// Route::get('/increment',[EmpControllers::class,'increment_details'])->middleware(['auth']);
// Route::get('/incrementEntry',[EmpControllers::class,'increment_Entry'])->middleware(['auth']);
// Route::get('/getEmpData', [EmpControllers::class,'empData'])->name('emp.data')->middleware(['auth']);
// Route::get('/tableData/{empId}', [EmpControllers::class,'tableData'])->name('t.data')->middleware(['auth']);

//Company Profile
Route::get('/companypf',[HrmSetupController::class,'companypf'])->middleware(['auth']);
Route::get('/comapnydt',[HrmSetupController::class,'companyDetails'])->middleware(['auth']);
Route::post('/cominsert',[HrmSetupController::class,'companyInsert'])->middleware(['auth']);
Route::post('/comUpdate',[HrmSetupController::class,'companyUpdate'])->middleware(['auth']);
Route::get('/destroyCom/{company_id}',[HrmSetupController::class,'destroyprof'])->name('destroyprofile')->middleware(['auth']);


//Attendence


// Route::get('/attend', [AttendanceController::class,'empData'])->name('att.index')->middleware(['auth']);
// Route::get('/attendsave',[AttendanceController::class,'store'])->name('att.store')->middleware(['auth']);
// Route::get('/attData', [AttendanceController::class,'attendData'])->name('att.data')->middleware(['auth']);
// Route::get('/attProcess', [AttendanceController::class,'getAttendenceData'])->name('att.process')->middleware(['auth']);
// Route::get('/attDeviceData', [AttendanceController::class,'attddevice'])->name('att.process')->middleware(['auth']);
// Route::get('/attDataProcess', [AttendanceController::class,'atdFullProcess'])->name('att.process')->middleware(['auth']);



//SALLARY


Route::get('/salEntry', [SalaryController::class,'companyList'])->middleware(['auth']);
Route::get('/showdata/{companyId}', [SalaryController::class,'showData'])->name('showdata')->middleware(['auth']);
Route::get('/savedata', [SalaryController::class,'storeData'])->middleware(['auth']);
// Route::get('/savedata', [SalaryController::class,'storeData']);
Route::get('/salProcess', [SalProcessController::class,'salProcess'])->middleware(['auth']);
Route::get('/salFullProcess', [SalProcessController::class,'salFullProcess'])->middleware(['auth']);
Route::get('/salProcessDell', [SalProcessController::class,'salProcessDell'])->middleware(['auth']);



// HRM REPORT

 Route::prefix('hrm/reports')->middleware(['web', 'auth'])->name('reports.')->group(function () {
 
    // WHEN-NEW-FORM-INSTANCE → loads report list dropdown
    Route::get('/', [ReportCenterController::class, 'index'])->name('center');
 
    // WHEN-LIST-CHANGED → SHOW_PRM_IN_MOOD → returns parameters + LOV options as JSON
    Route::get('/{reportId}/parameters', [ReportCenterController::class, 'getParameters'])
         ->whereNumber('reportId')
         ->name('parameters');
 
    // LOV options for a specific block_item (standalone endpoint, called by JS if needed)
    // e.g. GET /reports/lov/DEPT_NAME
    Route::get('/lov/{blockItem}', [ReportCenterController::class, 'getLovOptions'])
         ->name('lov');
 
    // WHEN-BUTTON-PRESSED → EXEC_REPORT → RUN_REPORT_ACTUAL → proxies PDF
    Route::post('/run', [ReportCenterController::class, 'runReport'])->name('run');
});



// Menu Hierarchy  →  ALL_MENU_HIERARCHY
Route::prefix('menus')->name('menus.')->group(function () {
    Route::get('/',          [MenuController::class, 'index'])->name('index');
    Route::post('/',         [MenuController::class, 'store'])->name('store');
    Route::get('/tree',      [MenuController::class, 'tree'])->name('tree');
    Route::get('/{id}/edit', [MenuController::class, 'edit'])->name('edit');
    Route::put('/{id}',      [MenuController::class, 'update'])->name('update');
    Route::delete('/{id}',   [MenuController::class, 'destroy'])->name('destroy');
});

// Route Entry  →  ALL_ROUTE_DETAILS
Route::prefix('routes')->name('routes.')->group(function () {
    Route::get('/',        [RouteController::class, 'index'])->name('index');
    Route::post('/',       [RouteController::class, 'store'])->name('store');
    Route::delete('/{id}', [RouteController::class, 'destroy'])->name('destroy');
});

// User Groups  →  ALL_USER_GROUP_MASTER
Route::prefix('groups')->name('groups.')->group(function () {
    Route::get('/',        [UserGroupController::class, 'index'])->name('index');
    Route::post('/',       [UserGroupController::class, 'store'])->name('store');
    Route::delete('/{id}', [UserGroupController::class, 'destroy'])->name('destroy');
});

// Group Menu Access  →  ALL_USER_GROUP_DETAILS (group-level toggles)
Route::prefix('group-menu')->name('group-menu.')->group(function () {
    Route::get('/',                     [GroupMenuController::class, 'index'])->name('index');
    Route::post('/{groupId}/save',      [GroupMenuController::class, 'save'])->name('save');
});

// Route Permissions  →  ALL_USER_SUB_DETAILS
Route::prefix('permissions')->name('permissions.')->group(function () {
    Route::get('/',                              [PermissionController::class, 'index'])->name('index');
    Route::post('/',                             [PermissionController::class, 'store'])->name('store');
    Route::put('/{groupId}/{subMenuId}',         [PermissionController::class, 'update'])->name('update');
    Route::delete('/{groupId}/{subMenuId}',      [PermissionController::class, 'destroy'])->name('destroy');
});

// Users  →  ALL_USER_INFO
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/AdminDashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/',          [UserController::class, 'index'])->name('index');
    Route::post('/',         [UserController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{id}',      [UserController::class, 'update'])->name('update');
    Route::delete('/{id}',   [UserController::class, 'destroy'])->name('destroy');
});

// User Menu Permission  →  ALL_USER_GROUP_DETAILS (user-level, USER_ID set)

Route::prefix('user-menu')->middleware(['auth'])->group(function () {

    // Page shell (col 1 users only, no heavy data)
    Route::get('/',                                [UserMenuPermissionController::class, 'index'])->name('user-menu.index');

    // AJAX: load all menus + enabled flags for a user  (col 2)
    Route::get('/{userId}/menus',                  [UserMenuPermissionController::class, 'getMenus'])->name('user-menu.menus');

    // AJAX: load all routes for a menu + enabled flags (col 3)
    Route::get('/{userId}/routes/{menuId}',        [UserMenuPermissionController::class, 'getRoutes'])->name('user-menu.routes');

    // Save permissions
    Route::post('/{userId}/save',                  [UserMenuPermissionController::class, 'save'])->name('user-menu.save');

    // Reset user overrides back to group defaults
    Route::post('/{userId}/reset',                 [UserMenuPermissionController::class, 'reset'])->name('user-menu.reset');
});

Route::prefix('user-company')->name('user-company.')->group(function () {

    // Page
    Route::get('/',                              [UserCompanyPermissionController::class, 'index'])
         ->name('index');

    // AJAX: fetch companies + enabled state for a user
    Route::get('/{userId}/companies',            [UserCompanyPermissionController::class, 'getCompanies'])
         ->name('companies');

    // AJAX: save permission changes
    Route::post('/{userId}/save',                [UserCompanyPermissionController::class, 'save'])
         ->name('save');

    // AJAX: delete user-level overrides
    Route::post('/{userId}/reset',               [UserCompanyPermissionController::class, 'reset'])
         ->name('reset');
});

/* ─────────────────────────────────────────────────────────────
   LOV ENDPOINTS (used by Select2 AJAX in the Blade view)
   Source: LovController.php (provided)
───────────────────────────────────────────────────────────── */
Route::prefix('lov')->name('lov.')->middleware(['auth'])->group(function () {

    /*
     | GET /lov/company?q=
     | Table: COMPANY_PROFILE JOIN COMPANY_PERMISSION
     | Source: team_emp.fmb → SELECT COMPANY_NAME, COMPANY_ID FROM COMPANY_PROFILE
     |         WHERE COMPANY_ID IN (SELECT COMPANY_ID FROM COMPANY_PERMISSION)
    */
    Route::get('/company',     [LovController::class, 'company'])->name('company');
    Route::get('/companyHrm',  [LovController::class, 'companyHrm'])->name('companyHrm');

    /*
     | GET /lov/dept?q=
     | Table: DEPT  →  {DEPT_NO, DEPT_NAME}
    */
    Route::get('/dept',        [LovController::class, 'dept'])->name('dept');

    /*
     | GET /lov/section?q=
     | Table: SECTION  →  {SECTION_NO, SECTION_NAME}
    */
    Route::get('/section',     [LovController::class, 'section'])->name('section');

    /*
     | GET /lov/floor?q=
     | Table: FLOOR  →  {FLOOR_ID, FLOOR_DESC}
    */
    Route::get('/floor',       [LovController::class, 'floor'])->name('floor');

    /*
     | GET /lov/line?q=
     | Table: LINE_INFO  →  {LINE_NO, LINE}
    */
    Route::get('/line',        [LovController::class, 'line'])->name('line');
    Route::get('/emp_type',        [LovController::class, 'emp_type'])->name('emp_type');

    /*
     | GET /lov/designation?q=
     | Table: DESIGNATION_DETAILS  →  {DES_ID, DESIGNATION_NAME}
    */
    Route::get('/designation', [LovController::class, 'designation'])->name('designation');

    /*
     | GET /lov/shift?q=
     | Table: HRM.SHIFT_INFO  →  {SHIFT_CODE, SHIFT_NAME}
    */
    Route::get('/shift',       [LovController::class, 'shift'])->name('shift');

    /*
     | GET /lov/weeklyoff
     | Static: Friday / Saturday / Sunday
    */
    Route::get('/weeklyoff',   [LovController::class, 'weeklyoff'])->name('weeklyoff');

    /*
     | GET /lov/yesno
     | Static: Y=Yes / N=No  (used for OT field)
    */
    Route::get('/yesno',       [LovController::class, 'yesno'])->name('yesno');
    Route::get('/workEnt',     [LovController::class, 'workEnt'])->name('workEnt');
});
Route::prefix('hrm/temp-emp')->name('temp-emp.')->group(function () {

    // GET  /temp-emp          → Render Blade view
    Route::get('/',                   [TempEmpController::class, 'index'])  ->name('index');

    // GET  /temp-emp/next-id  → Auto EMPNO: MAX(TO_NUMBER(EMPNO))+1
    Route::get('/next-id',            [TempEmpController::class, 'nextId']) ->name('next-id');

    // GET  /temp-emp/search?q=  → Quick toolbar search
    Route::get('/search',             [TempEmpController::class, 'search']) ->name('search');

    // GET  /temp-emp/lov?q=   → Employee LOV modal list
    Route::get('/lov',                [TempEmpController::class, 'lov'])    ->name('lov');

    // GET  /temp-emp/{empno}  → Load one record (personal + official)
    Route::get('/{empno}',            [TempEmpController::class, 'show'])   ->name('show');

    // POST /temp-emp          → Create (EMPNO auto-generated)
    Route::post('/',                  [TempEmpController::class, 'store'])  ->name('store');

    // PUT  /temp-emp/{empno}  → Update existing
    Route::put('/{empno}',            [TempEmpController::class, 'update']) ->name('update');

    // POST /temp-emp/{empno}/migrate → PB_TRANSFER: Temp → Permanent
    Route::post('/{empno}/migrate',   [TempEmpController::class, 'migrate'])->name('migrate');

    // DELETE /temp-emp/{empno} → Delete (cascades to emp_official)
    Route::delete('/{empno}',         [TempEmpController::class, 'destroy'])->name('destroy');
});





require base_path('routes/hrm/setup.php');
require base_path('routes/hrm/leave.php');
require base_path('routes/hrm/attendance_change.php');
require base_path('routes/hrm/attendance_process.php');
require base_path('routes/backup_route.php');
require base_path('routes/hrm/daily_office_food.php');


require base_path('routes/payroll/advance_loan.php');
require base_path('routes/payroll/salary_routes.php');
require base_path('routes/payroll/increment_entry.php');
require base_path('routes/payroll/festival_bonus.php');


