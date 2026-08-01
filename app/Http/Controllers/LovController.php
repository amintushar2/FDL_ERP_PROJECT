<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * LovController
 * Provides all List-of-Values (LOV) data as JSON for Select2 AJAX.
 * Each method returns { results: [{id, text}] } format.
 */
class LovController extends Controller
{
    private function auth() {
        if (!session('LoggedUser')) abort(401);
    }
  public function company(Request $request) {
        $this->auth();
        $rows =DB::table('company as cp')
    ->join('company_permission_user as cpu', 'cpu.company_id', '=', 'cp.company_id')
    ->join('F_STORE.ALL_USER_INFO as aui', 'aui.user_id', '=', 'cpu.user_id')
    ->where('aui.user_id', Auth::id()) // or 'AMIN'
    ->where('cp.is_active', 'Y')
    ->when($request->q, fn($q) =>
        $q->where(
            DB::raw('UPPER(cp.company_name)'),
            'like',
            '%' . strtoupper($request->q) . '%'
        )
    )
    ->select(
        'cp.company_id as id',
        DB::raw('cp.company_name as text')
    )
    ->orderBy('cp.company_name')
    ->limit(50)
    ->distinct()
    ->get();
        return response()->json(['results' => $rows]);
    }
     public function companyHrm(Request $request) {
        $this->auth();
        $rows = DB::table('company as cp')
    ->join('company_permission_user as cpu', 'cpu.company_id', '=', 'cp.company_id')
    ->join('F_STORE.ALL_USER_INFO as aui', 'aui.user_id', '=', 'cpu.user_id')
    ->where('aui.user_id', Auth::id()) // or 'AMIN'
    ->where('cp.is_active', 'Y')
    ->when($request->q, fn($q) =>
        $q->where(
            DB::raw('UPPER(cp.company_name)'),
            'like',
            '%' . strtoupper($request->q) . '%'
        )
    )
    ->select(
        'cp.company_id as id',
        DB::raw('cp.company_name as text')
    )
    ->orderBy('cp.company_name')
    ->limit(50)
    ->distinct()
    ->get();
        return response()->json(['results' => $rows]);
    }
    // Department  GET /lov/dept
    public function dept(Request $request) {
        $this->auth();
        $rows = DB::table('DEPT')
            ->select('DEPT_NO as id', DB::raw("DEPT_NAME as text"))
            ->when($request->q, fn($q) => $q->where(DB::raw('UPPER(DEPT_NAME)'), 'like', '%'.strtoupper($request->q).'%'))
            ->orderBy('DEPT_NO')->limit(50)->get();
        return response()->json(['results' => $rows]);
    }

    // Section  GET /lov/section
    public function section(Request $request) {
        $this->auth();
        $rows = DB::table('SECTION')
            ->select('SECTION_NO as id', DB::raw("SECTION_NAME as text"))
            ->when($request->q, fn($q) => $q->where(DB::raw('UPPER(SECTION_NAME)'), 'like', '%'.strtoupper($request->q).'%'))
            ->orderBy('SECTION_NO')->limit(50)->get();
        return response()->json(['results' => $rows]);
    }

    // Floor  GET /lov/floor
    public function floor(Request $request) {
        $this->auth();
        $rows = DB::table('FLOOR')
            ->select('FLOOR_ID as id', DB::raw("FLOOR_DESC as text"))
            ->when($request->q, fn($q) => $q->where(DB::raw('UPPER(FLOOR_DESC)'), 'like', '%'.strtoupper($request->q).'%'))
            ->orderBy('FLOOR_ID')->limit(50)->get();
        return response()->json(['results' => $rows]);
    }



    public function emp_type(Request $request) {
        $this->auth();
        $rows = DB::table('EMP_TYPE')
            ->select('EMP_TYPE as id', DB::raw("EMP_TYPE as text"))
            ->when($request->q, fn($q) => $q->where(DB::raw('UPPER(EMP_TYPE)'), 'like', '%'.strtoupper($request->q).'%'))
            ->orderBy('EMP_TYPE')->limit(50)->get();
        return response()->json(['results' => $rows]);
    }
    // Line  GET /lov/line
    public function line(Request $request) {
        $this->auth();
        $rows = DB::table('LINE_INFO')
            ->select('LINE_NO as id', DB::raw("LINE as text"))
            ->when($request->q, fn($q) => $q->where(DB::raw('UPPER(LINE)'), 'like', '%'.strtoupper($request->q).'%'))
            ->orderBy('LINE')->limit(50)->get();
        return response()->json(['results' => $rows]);
    }

    // Designation  GET /lov/designation
    public function designation(Request $request) {
        $this->auth();
        $rows = DB::table('DESIGNATION_DETAILS')
            ->select('DES_ID as id', DB::raw("DESIGNATION_NAME as text"))
            ->when($request->q, fn($q) => $q->where(DB::raw('UPPER(DESIGNATION_NAME)'), 'like', '%'.strtoupper($request->q).'%'))
            ->orderBy('DESIGNATION_NAME')->limit(200)->get();
        return response()->json(['results' => $rows]);
    }

    // Grade  GET /lov/grade
    public function grade(Request $request) {
        $this->auth();
        $rows = DB::table('HRM.GRADE')
            ->select('GRADE_ID as id', DB::raw("GRADE_NAME as text"))
            ->when($request->q, fn($q) => $q->where(DB::raw('UPPER(GRADE_NAME)'), 'like', '%'.strtoupper($request->q).'%'))
            ->orderBy('GRADE_NAME')->limit(50)->get();
        return response()->json(['results' => $rows]);
    }

    // Shift  GET /lov/shift
    public function shift(Request $request) {
        $this->auth();
        $rows = DB::table('HRM.SHIFT_INFO')
            ->select('SHIFT_CODE as id', DB::raw("SHIFT_NAME as text"))
            ->when($request->q, fn($q) => $q->where(DB::raw('UPPER(SHIFT_NAME)'), 'like', '%'.strtoupper($request->q).'%'))
            ->orderBy('SHIFT_NAME')->limit(50)->get();
        return response()->json(['results' => $rows]);
    }

    // Calendar  GET /lov/calendar
    public function calendar(Request $request) {
        $this->auth();
        $rows = DB::table('HRM.CALENDER_MASTER')
            ->select('CAL_CODE as id', DB::raw("CAL_CODE as text"))
            ->where('IS_CLOSE','N')
            ->when($request->q, fn($q) => $q->where(DB::raw('UPPER(CAL_CODE)'), 'like', '%'.strtoupper($request->q).'%'))
            ->orderBy('CAL_CODE')->limit(50)->get();
        return response()->json(['results' => $rows]);
    }

    // Weekly off (static)  GET /lov/weeklyoff
    public function weeklyoff() {
        $this->auth();
        return response()->json(['results' => [
            ['id'=>'Friday',   'text'=>'Friday'],
            ['id'=>'Saturday', 'text'=>'Saturday'],
            ['id'=>'Sunday',   'text'=>'Sunday'],
        ]]);
    }

    // Bank  GET /lov/bank
    public function bank(Request $request) {
        $this->auth();
        $rows = DB::table('BANK')
            ->select('BANK_NAME as id', DB::raw("BANK_NAME as text"))
            ->when($request->q, fn($q) => $q->where(DB::raw('UPPER(BANK_NAME)'), 'like', '%'.strtoupper($request->q).'%'))
            ->orderBy('BANK_NAME')->limit(50)->get();
        return response()->json(['results' => $rows]);
    }

    // Leave Category  GET /lov/leavecat
    public function leavecat(Request $request) {
        $this->auth();
        $rows = DB::table('LV_CAT_MASTER')
            ->select('LV_CAT_ID as id', DB::raw("LV_CAT_ID as text"))
            ->when($request->q, fn($q) => $q->where(DB::raw('UPPER(LV_CAT_ID)'), 'like', '%'.strtoupper($request->q).'%'))
            ->orderBy('LV_CAT_ID')->limit(50)->get();
        return response()->json(['results' => $rows]);
    }

    // Allowance Category  GET /lov/allwcat
    public function allwcat(Request $request) {
        $this->auth();
        $rows = DB::table('ALLW_CAT_MASTER')
            ->select('ALLW_CAT_ID as id', DB::raw("ALLW_CAT_ID as text"))
            ->when($request->q, fn($q) => $q->where(DB::raw('UPPER(ALLW_CAT_ID)'), 'like', '%'.strtoupper($request->q).'%'))
            ->orderBy('ALLW_CAT_ID')->limit(50)->get();
        return response()->json(['results' => $rows]);
    }

    // Thana/Upazila  GET /lov/thana
    public function thana(Request $request) {
        $this->auth();
        $rows = DB::table('HRM.UPAZILAS')
            ->select('NAME as id', DB::raw("NAME as text"))
            ->when($request->q, fn($q) => $q->where(DB::raw('UPPER(NAME)'), 'like', '%'.strtoupper($request->q).'%'))
            ->orderBy('NAME')->limit(50)->get();
        return response()->json(['results' => $rows]);
    }

    // District  GET /lov/district
    public function district(Request $request) {
        $this->auth();
        $rows = DB::table('HRM.DISTRICT')
            ->select('DISTRICT as id', DB::raw("DISTRICT as text"))
            ->when($request->q, fn($q) => $q->where(DB::raw('UPPER(DISTRICT)'), 'like', '%'.strtoupper($request->q).'%'))
            ->orderBy('DISTRICT')->limit(50)->get();
        return response()->json(['results' => $rows]);
    }

    // Y/N options (work_ent, ot_ent, res_ent, tran_ent, pf_ent, tax_ent)
    public function yesno() {
        return response()->json(['results' => [
            ['id'=>'Yes','text'=>'Yes'],
            ['id'=>'No','text'=>'No'],
        ]]);
    }
       public function workEnt() {
        return response()->json(['results' => [
            ['id'=>'Officer','text'=>'Officer'],
            ['id'=>'Worker','text'=>'Worker'],
            ['id'=>'Staff','text'=>'Staff'],
        ]]);
    }
}
