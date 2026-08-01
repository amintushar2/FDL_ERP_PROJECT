<?php

namespace App\Http\Controllers\HrmModule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveEntryController extends Controller
{
    // ──────────────────────────────────────────────────────────────
    // INDEX
    // ──────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $companies = $this->getPermittedCompanies();

        $query = DB::table('leave_entry_master as m')
            ->join('emp_personal as ep', 'ep.empno', '=', 'm.empno')
            ->join('emp_official as eo', 'eo.empno', '=', 'm.empno')
            ->select(
                'm.lv_cat_id', 'm.year', 'm.empno', 'm.company_id',
                'ep.new_empno',
                DB::raw("ep.first_name || ' ' || COALESCE(ep.middle_name,'') || ' ' || ep.last_name AS ename"),
                'eo.des_name'
            )
            ->whereIn('m.company_id', $companies->pluck('company_id'));

        if ($request->filled('empno'))      $query->where('m.empno', 'like', '%'.$request->empno.'%');
        if ($request->filled('new_empno'))  $query->where('ep.new_empno', 'like', '%'.$request->new_empno.'%');
        if ($request->filled('year'))       $query->where('m.year', $request->year);
        if ($request->filled('company_id')) $query->where('m.company_id', $request->company_id);

        $records = $query->orderByDesc('m.year')->orderBy('m.empno')->paginate(20);
        return view('hrm.leave.index', compact('records', 'companies'));
    }

    // ──────────────────────────────────────────────────────────────
    // CREATE
    // ──────────────────────────────────────────────────────────────
    public function create()
    {
        $companies  = $this->getPermittedCompanies();
        $leaveTypes = DB::table('leave_info')->select('leave_id', 'leave_name', 'max_days')->get();
        $years      = $this->buildYearList();
        return view('hrm.leave.create', compact('companies', 'leaveTypes', 'years'));
    }

    // ──────────────────────────────────────────────────────────────
    // STORE (AJAX – no redirect, returns JSON)
    // ──────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $this->validateMaster($request);

        if (!DB::table('emp_personal')
            ->where('empno', $request->empno)
            ->where('company_id', $request->company_id)
            ->count()) {
            return response()->json(['success' => false, 'message' => 'Employee not found in selected company.']);
        }

        // If master already exists → just save details, no error
        $existing = DB::table('leave_entry_master')
            ->where('empno', $request->empno)
            ->where('company_id', $request->company_id)
            ->where('year', $request->year)
            ->first();

        try {
            DB::transaction(function () use ($request, $existing) {
                if (!$existing) {
                    DB::table('leave_entry_master')->insert([
                        'lv_cat_id'  => $request->lv_cat_id,
                        'year'       => $request->year,
                        'empno'      => $request->empno,
                        'company_id' => $request->company_id,
                    ]);
                }

                // Clear existing details for this master then re-insert
                DB::table('leave_entry_details')
                    ->where('empno', $request->empno)
                    ->where('year', $request->year)
                    ->where('lv_cat_id', $request->lv_cat_id)
                    ->delete();

                foreach ($request->details ?? [] as $d) {
                    if (empty($d['leave_name'])) continue;
                    $this->insertDetail($request->empno, $request->year, $request->lv_cat_id, $d);
                }
            });

            return response()->json(['success' => true, 'message' => 'Leave entry saved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    // ──────────────────────────────────────────────────────────────
    // SHOW
    // ──────────────────────────────────────────────────────────────
    public function show($empno, $year, $company_id)
    {
        $master = $this->getMasterWithEmployee($empno, $year, $company_id);
        if (!$master) abort(404);
        $details  = $this->getDetails($empno, $year, $master->lv_cat_id);
        $balances = $this->computeBalances($empno, $year);
        $photoUrl = $this->getPhotoUrl($empno);
        return view('hrm.leave.show', compact('master', 'details', 'balances', 'photoUrl'));
    }

    // ──────────────────────────────────────────────────────────────
    // EDIT
    // ──────────────────────────────────────────────────────────────
    public function edit($empno, $year, $company_id)
    {
        $master = $this->getMasterWithEmployee($empno, $year, $company_id);
        if (!$master) abort(404);
        $details    = $this->getDetails($empno, $year, $master->lv_cat_id);
        $balances   = $this->computeBalances($empno, $year);
        $companies  = $this->getPermittedCompanies();
        $leaveTypes = DB::table('leave_info')->select('leave_id', 'leave_name', 'max_days')->get();
        $years      = $this->buildYearList();
        $photoUrl   = $this->getPhotoUrl($empno);
        return view('hrm.leave.edit', compact(
            'master', 'details', 'balances', 'companies', 'leaveTypes', 'years', 'photoUrl'
        ));
    }

    // ──────────────────────────────────────────────────────────────
    // UPDATE (AJAX – no redirect, returns JSON)
    // ──────────────────────────────────────────────────────────────
    public function update(Request $request, $empno, $year, $company_id)
    {
        $master = $this->getMasterWithEmployee($empno, $year, $company_id);
        if (!$master) return response()->json(['success' => false, 'message' => 'Record not found.']);

        try {
            DB::transaction(function () use ($request, $empno, $year, $master) {
                // Natural composite key: empno + leave_id + lv_from — no AUTO
                $existing = DB::table('leave_entry_details')
                    ->where('empno', $empno)->where('year', $year)->where('lv_cat_id', $master->lv_cat_id)
                    ->get(['leave_id','lv_from'])
                    ->map(fn($r) => $r->leave_id.'|'.$this->oracleDate($r->lv_from))
                    ->toArray();

                $submitted = collect($request->details ?? [])
                    ->filter(fn($d) => !empty($d['leave_id']) && !empty($d['lv_from_orig']))
                    ->map(fn($d) => $d['leave_id'].'|'.$this->oracleDate($d['lv_from_orig']))
                    ->toArray();

                // Delete rows the user removed
                foreach (array_diff($existing, $submitted) as $key) {
                    [$lid, $lfrom] = explode('|', $key, 2);
                    $row = DB::table('leave_entry_details')
                        ->where('empno', $empno)->where('leave_id', $lid)
                        ->whereRaw("lv_from = TO_DATE(?, 'YYYY-MM-DD')", [$this->oracleDate($lfrom)])->first();
                    if ($row) {
                        if ($row->leave_name === 'Earned Leave') {
                            DB::table('emp_extra')->where('empno', $empno)
                                ->increment('earn_leave_balance', (float)$row->approve_days);
                        }
                        $this->revertAttendance($empno, $row->lv_from, $row->lv_to);
                        DB::table('leave_entry_details')
                            ->where('empno', $empno)->where('leave_id', $lid)
                            ->whereRaw("lv_from = TO_DATE(?, 'YYYY-MM-DD')", [$this->oracleDate($lfrom)])->delete();
                    }
                }

                foreach ($request->details ?? [] as $d) {
                    if (empty($d['leave_name'])) continue;
                    $key = ($d['leave_id']??'').'|'.($d['lv_from_orig']??'');
                    if (!empty($d['leave_id']) && !empty($d['lv_from_orig']) && in_array($key, $existing)) {
                        $this->updateDetail($empno, $d);
                    } else {
                        $this->insertDetail($empno, $year, $master->lv_cat_id, $d);
                    }
                }
            });

            return response()->json(['success' => true, 'message' => 'Leave entry updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    // ──────────────────────────────────────────────────────────────
    // DESTROY MASTER
    // ──────────────────────────────────────────────────────────────
    public function destroy($empno, $year, $company_id)
    {
        $master = $this->getMasterWithEmployee($empno, $year, $company_id);
        if (!$master) abort(404);

        if (DB::table('leave_entry_details')
            ->where('lv_cat_id', $master->lv_cat_id)
            ->where('year', $year)->where('empno', $empno)->count()) {
            return response()->json(['success' => false, 'message' => 'Remove all leave details first.']);
        }

        DB::table('leave_entry_master')
            ->where('empno', $empno)->where('year', $year)->where('company_id', $company_id)->delete();
        return response()->json(['success' => true, 'message' => 'Leave entry deleted.']);
    }

    // ──────────────────────────────────────────────────────────────
    // DESTROY DETAIL (AJAX)
    // ──────────────────────────────────────────────────────────────
    public function destroyDetail(Request $request, $empno, $leaveId, $lvFrom)
    {
        $lvFrom = $this->oracleDate($lvFrom);
        $leaveName = $this->leaveNameById($leaveId);

        $row = DB::table('leave_entry_details')
            ->where('empno', $empno)
            ->where(function ($query) use ($leaveId, $leaveName) {
                $query->where('leave_id', $leaveId);
                if ($leaveName) {
                    $query->orWhere('leave_name', $leaveName);
                }
            })
            ->whereRaw("lv_from = TO_DATE(?, 'YYYY-MM-DD')", [$lvFrom])
            ->first();
        if (!$row) return response()->json(['error' => 'Record not found.'], 404);

        DB::transaction(function () use ($row, $empno) {
            if ($row->leave_name === 'Earned Leave') {
                DB::table('emp_extra')->where('empno', $empno)
                    ->increment('earn_leave_balance', (float)$row->approve_days);
            }
            $this->revertAttendance($empno, $row->lv_from, $row->lv_to);
            DB::table('leave_entry_details')
                ->where('empno', $empno)
                ->where('leave_id', $row->leave_id)
                ->whereRaw("lv_from = TO_DATE(?, 'YYYY-MM-DD')", [$this->oracleDate($row->lv_from)])
                ->delete();
        });

        return response()->json(['success' => true]);
    }

    // ──────────────────────────────────────────────────────────────
    // LEAVE SLIP (replaces leave_slip.RDF)
    // ──────────────────────────────────────────────────────────────
    public function leaveSlip(Request $request, $empno, $leaveId, $lvFrom)
    {
        $lvFrom = $this->oracleDate($lvFrom);
        $leaveName = $this->leaveNameById($leaveId);

        // Fetch detail by natural composite key: empno + leave_id + lv_from
        $detail = DB::table('leave_entry_details')
            ->where('empno', $empno)
            ->where(function ($query) use ($leaveId, $leaveName) {
                $query->where('leave_id', $leaveId);
                if ($leaveName) {
                    $query->orWhere('leave_name', $leaveName);
                }
            })
            ->whereRaw("lv_from = TO_DATE(?, 'YYYY-MM-DD')", [$lvFrom])
            ->first();
        if (!$detail) abort(404, 'Leave detail not found.');

        // Mirrors RDF query: JOIN master+details+emp_personal+emp_official+dept+company
        // Params: P_EMP_NO, P_YEAR, P_LEAVE_ID, P_DATE (lv_from)
        $master = DB::table('leave_entry_master as m')
            ->join('leave_entry_details as d', function ($j) {
                $j->on('d.empno', '=', 'm.empno')->on('d.year', '=', 'm.year');
            })
            ->join('emp_personal as ep', 'ep.empno', '=', 'm.empno')
            ->join('emp_official as eo', 'ep.empno', '=', 'eo.empno')
            ->leftJoin('dept as dept', 'dept.dept_no', '=', 'eo.dept_no')
            ->leftJoin('company_profile as cp', 'cp.company_id', '=', 'm.company_id')
            ->select(
                'm.empno', 'm.year', 'm.company_id', 'm.lv_cat_id',
                'ep.new_empno', 'ep.father_name',
                DB::raw("ep.first_name || ' ' || COALESCE(ep.middle_name,'') || ' ' || ep.last_name AS ename"),
                'eo.des_name', 'eo.dept_name', 'eo.section_name',
                'eo.joining_date', 'eo.conform_date',
                'cp.company_name', 'cp.COMPANY_ADDRESS AS address', 'cp.logo_location', 
                'd.approve_days', 'd.leave_name', 'd.leave_id',
                'd.remax', 'd.lv_from', 'd.lv_to',
                'd.pre_balance', 'd.balance',
                'd.approve_by', 'd.approve_date', 'd.application_date',
                'd.information',
                DB::raw("'HR Manager' AS approve_authority")
            )
            ->where('m.empno', $empno)
            ->where('m.year', $detail->year)
            ->where(function ($query) use ($leaveId, $leaveName) {
                $query->where('d.leave_id', $leaveId);
                if ($leaveName) {
                    $query->orWhere('d.leave_name', $leaveName);
                }
            })
            ->whereRaw("d.lv_from = TO_DATE(?, 'YYYY-MM-DD')", [$this->oracleDate($detail->lv_from)])
            ->first();

        if (!$master) abort(404, 'Leave master not found.');

        $leaveBalances = $this->computeLeaveSlipBalances($empno, $detail->year);
        $photoUrl      = $this->getPhotoUrl($empno);

        return view('hrm.leave.slip', compact('master', 'detail', 'photoUrl', 'leaveBalances'));
    }

    // ──────────────────────────────────────────────────────────────
    // AJAX: employee lookup by NEW_EMPNO
    // Returns empno + new_empno + name + designation + lv_cat_id
    // ──────────────────────────────────────────────────────────────
    public function getEmployee(Request $request)
    {
        // Search by new_empno (the visible employee number used in HR)
        $emp = DB::table('emp_personal as ep')
            ->join('emp_official as eo', 'eo.empno', '=', 'ep.empno')
            ->select(
                'ep.empno',
                'ep.new_empno',
                DB::raw("ep.first_name || ' ' || COALESCE(ep.middle_name,'') || ' ' || ep.last_name AS ename"),
                'eo.des_name', 'eo.lv_cat_id', 'eo.conform_date', 'eo.joining_date'
            )
            ->where('ep.new_empno', $request->new_empno)
            ->where('ep.company_id', $request->company_id)
            ->first();

        if (!$emp) return response()->json(['error' => 'Employee not found.'], 404);

        return response()->json([
            'empno'     => $emp->empno,
            'new_empno' => $emp->new_empno,
            'ename'     => trim($emp->ename),
            'des_name'  => $emp->des_name,
            'lv_cat_id' => $emp->lv_cat_id,
            'photo_url' => $this->getPhotoUrl($emp->empno),
        ]);
    }

    public function getBalances(Request $request)
    {
        return response()->json($this->computeBalances($request->empno, $request->year));
    }

    // ──────────────────────────────────────────────────────────────
    // AJAX: load existing details for the selected year
    // ──────────────────────────────────────────────────────────────
    public function getPreviousYearDetails(Request $request)
    {
        $empno    = $request->empno;
        $year     = (int)$request->year;
        $prevYear = $year; // use selected year

        $prevMaster = DB::table('leave_entry_master')
            ->where('empno', $empno)->where('year', $prevYear)->first();

        if (!$prevMaster) return response()->json(['found' => false, 'details' => []]);

        $prevDetails = DB::table('leave_entry_details')
            ->leftJoin('leave_info', 'leave_info.leave_name', '=', 'leave_entry_details.leave_name')
            ->where('empno', $empno)->where('year', $prevYear)
            ->where('lv_cat_id', $prevMaster->lv_cat_id)
            ->orderBy('lv_from')
            ->get([
                'leave_entry_details.*',
                DB::raw('COALESCE(leave_entry_details.leave_id, leave_info.leave_id) AS resolved_leave_id'),
            ]);

        return response()->json([
            'found'     => true,
            'prev_year' => $prevYear,
            'lv_cat_id' => $prevMaster->lv_cat_id,
            'details'   => $prevDetails->map(fn($d) => [

                'empno'            => $d->empno,         // needed for slip button
                'leave_id'         => $d->resolved_leave_id,
                'leave_name'       => $d->leave_name,
                'approve_days'     => $d->approve_days,
                'approve_by'       => $d->approve_by ?? '',
                'information'      => $d->information ?? '',
                'remax'            => $d->remax ?? '',
                'pre_balance'      => $d->pre_balance,
                'balance'          => $d->balance,
                'max_days'         => $d->max_days,
                'lv_from'          => $this->oracleDate($d->lv_from),
                'lv_to'            => $this->oracleDate($d->lv_to),
                'application_date' => $this->oracleDate($d->application_date),
                'approve_date'     => $this->oracleDate($d->approve_date),
            ]),
        ]);
    }

    public function validateLeaveDay(Request $request)
    {
        $leaveInfo = DB::table('leave_info')->where('leave_name', $request->leave_name)->first();
        if (!$leaveInfo) return response()->json(['valid' => false, 'message' => 'Leave type not found.']);

        $official = DB::table('emp_official')->where('empno', $request->empno)->first();
        if (!$official) return response()->json(['valid' => false, 'message' => 'Employee record not found.']);

        $maxDays = $leaveInfo->max_days ?? 60;

        if (strtolower($leaveInfo->leave_name) === 'maternity leave') {
            $sex = DB::table('emp_personal')->where('empno', $request->empno)->value('sex');
            if (strtoupper($sex) === 'M') {
                return response()->json(['valid' => false, 'message' => 'Males cannot take maternity leave.']);
            }
        }

        $enjoyed = DB::table('leave_entry_details')
            ->where('leave_id', $leaveInfo->leave_id)->where('empno', $request->empno)
            ->whereRaw("TO_CHAR(lv_to,'YYYY') = ?", [$request->year])->sum('approve_days');

        if (($enjoyed + (int)$request->leave_days) > $maxDays) {
            return response()->json(['valid' => false,
                'message'  => "Exceeds maximum ({$maxDays} days). Already enjoyed: {$enjoyed}.",
                'max_days' => $maxDays, 'enjoyed' => $enjoyed]);
        }

        return response()->json(['valid' => true, 'max_days' => $maxDays, 'enjoyed' => $enjoyed]);
    }

    // ══════════════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ══════════════════════════════════════════════════════════════

    private function getPermittedCompanies()
    {
        return DB::table('company_profile as cp')
    ->join('company_permission_user as cpu', 'cpu.company_id', '=', 'cp.company_id')
    ->join('F_STORE.ALL_USER_INFO as aui', 'aui.user_id', '=', 'cpu.user_id')
    ->where('aui.user_id', Auth::id()) // or 'AMIN'
    ->where('cp.is_active', 'Y')
                ->select('cp.company_id', 'cp.company_name')
    ->orderBy('cp.company_name')
    ->limit(50)
    ->distinct()
    ->get();
        
        
    }

    private function getMasterWithEmployee($empno, $year, $companyId)
    {
        return DB::table('leave_entry_master as m')
            ->join('emp_personal as ep', 'ep.empno', '=', 'm.empno')
            ->join('emp_official as eo', 'eo.empno', '=', 'm.empno')
            ->select(
                'm.lv_cat_id', 'm.year', 'm.empno', 'm.company_id',
                'ep.new_empno',
                DB::raw("ep.first_name || ' ' || COALESCE(ep.middle_name,'') || ' ' || ep.last_name AS ename"),
                'eo.des_name', 'eo.conform_date', 'eo.joining_date'
            )
            ->where('m.empno', $empno)->where('m.year', $year)->where('m.company_id', $companyId)->first();
    }

    private function getDetails($empno, $year, $lvCatId)
    {
        return DB::table('leave_entry_details')
            ->where('empno', $empno)->where('year', $year)->where('lv_cat_id', $lvCatId)
            ->orderBy('lv_from')->get()
            ->map(function ($detail) {
                foreach (['lv_from', 'lv_to', 'application_date', 'approve_date'] as $field) {
                    $detail->{$field} = $this->oracleDate($detail->{$field});
                }

                return $detail;
            });
    }

    private function computeBalances($empno, $year)
    {
        // Use BALANCE column from leave_entry_details (stores running balance)
        $balRow = fn($name) => DB::table('leave_entry_details')
            ->where('leave_name', $name)->where('empno', $empno)->where('year', $year)
            ->orderBy('lv_from', 'desc')->first();

        $enjoyed = fn($name) => DB::table('leave_entry_details')
            ->where('leave_name', $name)->where('empno', $empno)->where('year', $year)
            ->sum('approve_days');

        $calcMax = fn($name) => DB::table('leave_info')->where('leave_name', $name)->value('max_days') ?? 0;

        $cMax = $calcMax('Casual Leave');  $cEnj = $enjoyed('Casual Leave');
        $sMax = $calcMax('Sick Leave');    $sEnj = $enjoyed('Sick Leave');

        $earnBal = DB::table('emp_extra')->where('empno', $empno)->value('earn_leave_balance') ?? 0;
        $eEnj    = $enjoyed('Earned Leave');
        $eMax    = $earnBal + $eEnj;

        return [
            'casual_total'   => $cMax, 'casual_enjoyed' => $cEnj, 'casual_balance' => $cMax - $cEnj,
            'sick_total'     => $sMax, 'sick_enjoyed'   => $sEnj, 'sick_balance'   => $sMax - $sEnj,
            'earn_total'     => $eMax, 'earn_enjoyed'   => $eEnj, 'earn_balance'   => $eMax - $eEnj,
        ];
    }

    private function computeLeaveSlipBalances($empno, $year): array
    {
        $sumById = fn($id) => (float)DB::table('leave_entry_details')
            ->where('empno', $empno)->where('year', $year)->where('leave_id', $id)->sum('approve_days');
        $maxById = fn($id) => (float)(DB::table('leave_info')->where('leave_id', $id)->value('max_days') ?? 0);

        $cf_cl = $sumById('L-01'); $cf_ml = $sumById('L-02'); $cf_el = $sumById('L-04');
        $earnRunning = (float)(DB::table('emp_extra')->where('empno', $empno)->value('earn_leave_balance') ?? 0);

        return [
            'cf_cl'      => $cf_cl, 'cf_ml'      => $cf_ml, 'cf_el'      => $cf_el,
            'max_cl'     => $maxById('L-01'), 'max_ml' => $maxById('L-02'), 'max_el' => $maxById('L-04'),
            'balance_cl' => $maxById('L-01') - $cf_cl,
            'balance_ml' => $maxById('L-02') - $cf_ml,
            'balance_el' => $earnRunning,
        ];
    }

    private function insertDetail($empno, $year, $lvCatId, array $d)
    {
        $ap = (float)($d['approve_days'] ?? 0);
        $pre = (float)($d['pre_balance'] ?? 0);

        DB::table('leave_entry_details')->insert([
            'lv_cat_id'        => $lvCatId,
            'year'             => $year,
            'empno'            => $empno,
            'leave_name'       => $d['leave_name'],
            'leave_id'         => $d['leave_id'] ?? null,
            'max_days'         => $d['max_days'] ?? null,
            'lv_from'          => $this->oracleDate($d['lv_from'] ?? null),
            'lv_to'            => $this->oracleDate($d['lv_to'] ?? null),
            'application_date' => $this->oracleDate($d['application_date'] ?? null),
            'application_no'   => $d['application_no'] ?? null,
            'approve_date'     => $this->oracleDate($d['approve_date'] ?? null),
            'approve_days'     => $ap,
            'approve_by'       => $d['approve_by'] ?? null,    // existing APPROVE_BY column
            'pre_balance'      => $pre,                        // existing PRE_BALANCE column
            'balance'          => $pre - $ap,                  // existing BALANCE column
            'remax'            => $d['remax'] ?? null,         // existing REMAX column
            'information'      => $d['information'] ?? null,   // existing INFORMATION column
        ]);

        if (($d['leave_name'] ?? '') === 'Earned Leave') {
            DB::table('emp_extra')->where('empno', $empno)->decrement('earn_leave_balance', $ap);
        }
        if (!empty($d['lv_from']) && !empty($d['lv_to'])) {
            $this->updateAttendance($empno, $d['lv_from'], $d['lv_to'], $d['leave_name']);
        }
    }

    private function updateDetail($empno, array $d)
    {
        // Use lv_from_orig as the original key (lv_from may have been edited)
        $origFrom = $this->oracleDate($d['lv_from_orig'] ?? $d['lv_from']);
        $old = DB::table('leave_entry_details')
            ->where('empno', $empno)
            ->where('leave_id', $d['leave_id'])
            ->whereRaw("lv_from = TO_DATE(?, 'YYYY-MM-DD')", [$origFrom])
            ->first();
        $ap  = (float)($d['approve_days'] ?? 0);
        $pre = (float)($d['pre_balance'] ?? 0);

        DB::table('leave_entry_details')
            ->where('empno', $empno)
            ->where('leave_id', $d['leave_id'])
            ->whereRaw("lv_from = TO_DATE(?, 'YYYY-MM-DD')", [$origFrom])
            ->update([
                'leave_name'       => $d['leave_name'],
                'leave_id'         => $d['leave_id'] ?? null,
                'max_days'         => $d['max_days'] ?? null,
                'lv_from'          => $this->oracleDate($d['lv_from'] ?? null),
                'lv_to'            => $this->oracleDate($d['lv_to'] ?? null),
                'application_date' => $this->oracleDate($d['application_date'] ?? null),
                'application_no'   => $d['application_no'] ?? null,
                'approve_date'     => $this->oracleDate($d['approve_date'] ?? null),
                'approve_days'     => $ap,
                'approve_by'       => $d['approve_by'] ?? null,
                'pre_balance'      => $pre,
                'balance'          => $pre - $ap,
                'remax'            => $d['remax'] ?? null,
                'information'      => $d['information'] ?? null,
            ]);

        if ($old && $old->leave_name === 'Earned Leave') {
            $delta = $old->approve_days - $ap;
            if ($delta > 0)     DB::table('emp_extra')->where('empno', $empno)->increment('earn_leave_balance', $delta);
            elseif ($delta < 0) DB::table('emp_extra')->where('empno', $empno)->decrement('earn_leave_balance', abs($delta));
        }

        if ($old) $this->revertAttendance($empno, $old->lv_from, $old->lv_to);
        if (!empty($d['lv_from']) && !empty($d['lv_to'])) {
            $this->updateAttendance($empno, $d['lv_from'], $d['lv_to'], $d['leave_name']);
        }
    }

    private function updateAttendance($empno, $lvFrom, $lvTo, $leaveName)
    {
        $lvFrom = $this->oracleDate($lvFrom);
        $lvTo = $this->oracleDate($lvTo);

        $map = [
            'Casual Leave' => ['CL','CL'], 'Earned Leave' => ['EL','EL'],
            'Sick Leave' => ['SL','SL'], 'Tour Program' => ['T','T'],
            'Maternity Leave' => ['ML','ML'], 'Leave Without Pay' => ['LWP','LWTP'],
            'Leave With Pay' => ['LWP','LWP'], 'Leave With No Pay' => ['LWNP','LWNP'],
            'Contractual Leave' => ['CONL','CONL'], 'Emergency Leave' => ['EMGL','EMGL'],
            'Leave With Special Consideration' => ['LWSC','LWSC'],
            'Compensotion Leave' => ['COML','COML'],
        ];
        if (!isset($map[$leaveName])) return;
        [$s, $s2] = $map[$leaveName];
        $q = DB::table('attendance_details')
            ->where('empno', $empno)
            ->whereRaw("att_date >= TO_DATE(?, 'YYYY-MM-DD')", [$lvFrom])
            ->whereRaw("att_date <= TO_DATE(?, 'YYYY-MM-DD')", [$lvTo]);
        if ($leaveName === 'Earned Leave') {
            $q->whereNotIn('status', ['AWO', 'WH', 'CDH']);
        }
        $q->update(['status' => $s, 'status2' => $s2]);
    }

    private function revertAttendance($empno, $lvFrom, $lvTo)
    {
        if (!$lvFrom || !$lvTo) return;
        $lvFrom = $this->oracleDate($lvFrom);
        $lvTo = $this->oracleDate($lvTo);

        // Use whereRaw for Oracle DATE comparison (handles TIMESTAMP/DATE type)
        DB::table('attendance_details')
            ->where('empno', $empno)
            ->whereRaw("att_date >= TO_DATE(?, 'YYYY-MM-DD')", [$lvFrom])
            ->whereRaw("att_date <= TO_DATE(?, 'YYYY-MM-DD')", [$lvTo])
            ->update(['status' => 'A', 'status2' => 'A']);
    }

    private function getPhotoUrl($empno)
    {
        // Image server: http://192.168.210.205:81
        return 'http://192.168.210.205:81/' . $empno . '.jpg';
    }

    private function leaveNameById($leaveId): ?string
    {
        if (!$leaveId) return null;

        return DB::table('leave_info')->where('leave_id', $leaveId)->value('leave_name');
    }

    private function oracleDate($value): ?string
    {
        if (!$value) return null;

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return substr((string)$value, 0, 10);
        }
    }

    private function buildYearList(): array { return range(now()->year - 5, now()->year + 1); }

    private function validateMaster(Request $request): void
    {
        $request->validate([
            'company_id' => 'required',
            'empno'      => 'required',
            'year'       => 'required|digits:4',
            'lv_cat_id'  => 'required',
        ]);
    }
}
