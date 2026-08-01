<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalaryController extends Controller
{
    /* ══════════════════════════════════════════════════════════════════
       INDEX – Blade View
    ══════════════════════════════════════════════════════════════════ */
    public function index()
    {
        $companies = DB::table('company_profile as cp')
            ->join('company_permission as cpm', 'cp.company_id', '=', 'cpm.company_id')
            ->select('cp.company_id', 'cp.company_name')
            ->orderBy('cp.company_name')
            ->distinct()
            ->get();

        return view('payroll.salary.index', compact('companies'));
    }

    /* ══════════════════════════════════════════════════════════════════
       GET EMP_PAYMENT ENTRIES  (main working table)
       Mirrors: EMP_PAYMENT block query
       SELECT * FROM EMP_PAYMENT WHERE COMPANY_ID = :b1 AND OT_ENT = 'No'
    ══════════════════════════════════════════════════════════════════ */
    public function getEntries(Request $request): JsonResponse
    {
        $request->validate(['company_id' => 'required']);

        /*
         * ORA-00918 fix: use explicit column list instead of ep.*
         * Exact EMP_PAYMENT columns from Oracle DDL:
         * EMPNO, GROSS, BASIC, HR_RATE, MR_RATE, OTHERS_RATE,
         * HR_AMT, MR_AMT, OTHERS_AMT, STAMP, TRANSPORT, CONVANCE,
         * GRADE, SECTION, DES_NAME, ATND_BONUS, FEST_BONUS, OTHERS,
         * OT_ENT, OT_RATE, ALLOWANCE, TAX, FOOD_ALLOWANCE,
         * EMP_GRADE, COMPANY_ID, NEW_EMPNO
         */
        $query = DB::table('emp_payment as ep')
            ->leftJoin('emp_official as eo', function ($j) {
                $j->on('ep.empno', '=', 'eo.empno')
                  ->on('ep.company_id', '=', 'eo.company_id');
            })
            ->where('ep.company_id', $request->company_id)
            ->where('ep.ot_ent', 'No')
            ->select(
                // EMP_PAYMENT explicit columns (no ep.* to avoid ORA-00918)
                'ep.empno',
                'ep.new_empno',
                'ep.gross',
                'ep.basic',
                'ep.hr_rate',
                'ep.mr_rate',
                'ep.others_rate',
                'ep.hr_amt',
                'ep.mr_amt',
                'ep.others_amt',
                'ep.stamp',
                'ep.transport',
                'ep.convance',
                'ep.grade',
                'ep.section',
                'ep.des_name',
                'ep.atnd_bonus',
                'ep.fest_bonus',
                'ep.others',
                'ep.ot_ent',
                'ep.ot_rate',
                'ep.allowance',
                'ep.tax',
                'ep.food_allowance',
                'ep.emp_grade',
                'ep.company_id',
                // Derived columns from DB functions
                DB::raw("HRM.GET_EMP_NAME(ep.empno) AS emp_name"),
                DB::raw("get_dept_name(eo.dept_no)  AS dept_name"),
                DB::raw("get_section(eo.section_no) AS section_name")
            )
            ->orderBy('ep.empno');

        if ($request->filled('emp_no')) {
            $query->where('ep.empno', 'like', '%' . $request->emp_no . '%');
        }

        return response()->json(['data' => $query->limit(3000)->get()]);
    }

    /* ══════════════════════════════════════════════════════════════════
       GET SINGLE EMP_PAYMENT ENTRY  (for edit form)
    ══════════════════════════════════════════════════════════════════ */
    public function getEntry(Request $request): JsonResponse
    {
        $request->validate(['emp_no' => 'required', 'company_id' => 'required']);

        $row = DB::table('emp_payment as ep')
            ->leftJoin('emp_official as eo', function ($j) {
                $j->on('ep.empno', '=', 'eo.empno')
                  ->on('ep.company_id', '=', 'eo.company_id');
            })
            ->leftJoin('emp_personal as epr', 'ep.empno', '=', 'epr.empno')
            ->where('ep.empno',      $request->emp_no)
            ->where('ep.company_id', $request->company_id)
            ->select(
                // Explicit EMP_PAYMENT columns
                'ep.empno', 'ep.new_empno', 'ep.gross', 'ep.basic',
                'ep.hr_rate', 'ep.mr_rate', 'ep.others_rate',
                'ep.hr_amt', 'ep.mr_amt', 'ep.others_amt',
                'ep.stamp', 'ep.transport', 'ep.convance', 'ep.grade',
                'ep.section', 'ep.des_name', 'ep.atnd_bonus', 'ep.fest_bonus',
                'ep.others', 'ep.ot_ent', 'ep.ot_rate', 'ep.allowance',
                'ep.tax', 'ep.food_allowance', 'ep.emp_grade', 'ep.company_id',
                // From emp_official
                'eo.emp_type',
                DB::raw("TO_CHAR(eo.joining_date,'DD-Mon-YYYY') AS joining_date"),
                // DB functions
                DB::raw("HRM.GET_EMP_NAME(ep.empno) AS emp_name"),
                DB::raw("get_dept_name(eo.dept_no)  AS dept_name"),
                DB::raw("get_section(eo.section_no) AS section_name")
            )
            ->first();

        if (!$row) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        return response()->json(['data' => $row]);
    }

    /* ══════════════════════════════════════════════════════════════════
       GROSS ENTRY BUTTON  –  PB_GROSS WHEN-BUTTON-PRESSED
       Mirrors Oracle:
         1. DO_KEY('Commit_Form')  → save current EMP_PAYMENT record
         2. Cursor c_gross:
              SELECT DISTINCT ep.empno, ep.new_empno,
                     ep.first_name||' '||ep.last_name ename,
                     eo.des_name, eo.gross,
                     replace(eo.grade_id,'Grade-',null) grade
              FROM emp_personal ep, emp_official eo,
                   (SELECT empno FROM emp_payment WHERE ot_ent='No') p
              WHERE ep.empno = eo.empno
              AND   ep.company_id = :company_id
              AND   p.empno IS NULL    ← employees NOT yet in emp_payment
         3. For rec in c_gross → INSERT into EMP_PAYMENT for each
    ══════════════════════════════════════════════════════════════════ */
    public function grossEntry(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'company_id'  => 'required',
                'salary_date' => 'required',
            ]);

            $companyId = $request->company_id;
            $salaryDate = $this->parseDate($request->salary_date);

            if (!$salaryDate) {
                return response()->json([
                    'message' => 'Invalid salary date format. Use dd-Mon-yyyy (e.g. 15-May-2026)',
                ], 422);
            }

            // Calculate first day and last day of salary month
            $firstDayOfMonth = $salaryDate->copy()->startOfMonth();
            $lastDayOfMonth  = $salaryDate->copy()->endOfMonth();

            \Log::info("GROSS_ENTRY: salary_date={$salaryDate->format('d-M-Y')}, month range: {$firstDayOfMonth->format('d-M-Y')} to {$lastDayOfMonth->format('d-M-Y')}");

            // c_gross cursor: get distinct employees with attendance in salary month
            try {
                $employees = DB::table('emp_personal as ep')
                    ->join('emp_official as eo', function ($j) {
                        $j->on('ep.empno', '=', 'eo.empno')
                          ->on('eo.company_id', '=', 'ep.company_id');
                    })
                    ->join('attendance_details as ad', 'ep.empno', '=', 'ad.empno')
                    ->where('ep.company_id', $companyId)
                    ->whereBetween(DB::raw('TRUNC(ad.att_date)'), [
                        $firstDayOfMonth->format('Y-m-d'),
                        $lastDayOfMonth->format('Y-m-d')
                    ])
                    ->whereRaw('COALESCE(eo.gross, 0) > 0')
                    ->select(
                        'ep.empno',
                        'ep.new_empno',
                        DB::raw("TRIM(ep.first_name||'  '||COALESCE(ep.middle_name,'')||'  '||ep.last_name) AS ename"),
                        'eo.des_name',
                        'eo.gross',
                        'eo.tax_deduction',
                        DB::raw("NULLIF(REPLACE(eo.grade_id,'Grade-',''), '') AS grade")
                    )
                    ->distinct()
                    ->orderBy('ep.empno')
                    ->get();
                
            } catch (\Exception $e) {
                \Log::error("GROSS_ENTRY: Error fetching employees - " . $e->getMessage());
                return response()->json([
                    'message' => 'Error fetching employees: ' . $e->getMessage(),
                    'error' => $e->getLine() . ' - ' . $e->getFile(),
                    'inserted' => 0,
                    'data' => [],
                ], 500);
            }

        if ($employees->isEmpty()) {
            \Log::warning("GROSS_ENTRY: No employees found with attendance in {$salaryDate->format('M-Y')}");
            return response()->json([
                'message'  => "No employees found with attendance in {$salaryDate->format('F Y')}.",
                'inserted' => 0,
                'data'     => [],
            ]);
        }

        // Load salary parameters for auto-calculation (handled per-employee in loop)
        
        DB::beginTransaction();
        try {
            // Clear existing EMP_PAYMENT records
            try {
                $deleted = DB::table('emp_payment')->delete();
                \Log::info("GROSS_ENTRY: Cleared {$deleted} existing records from emp_payment");
            } catch (\Exception $e) {
                \Log::error("GROSS_ENTRY: Error clearing emp_payment table - " . $e->getMessage());
                throw $e;
            }

            $inserted = 0;
            $rows     = [];

            foreach ($employees as $emp) {
                try {
                    $v_basic = (float)($emp->gross ?? 0);

                    // ══════════════════════════════════════════════════
                    // Fetch all salary parameters (single query)
                    // ══════════════════════════════════════════════════
                    try {
                        $param = DB::table('salary_parameter')
                            ->select('stamp_amt', 'hr_per', 'mr_amt', 'convance_amt', 'food_amt')
                            ->first();
                        
                        $v_stamp    = (float)($param->stamp_amt ?? 0);
                        $v_hr       = (float)($param->hr_per ?? 0);
                        $v_mr       = (float)($param->mr_amt ?? 0);
                        $v_convance = (float)($param->convance_amt ?? 0);
                        $v_food     = (float)($param->food_amt ?? 0);
                    } catch (\Exception $e) {
                        // Default values if query fails
                        $v_stamp    = 0;
                        $v_hr       = 0;
                        $v_mr       = 0;
                        $v_convance = 0;
                        $v_food     = 0;
                        \Log::warning("GROSS_ENTRY: Error fetching salary parameters for {$emp->empno}, using defaults");
                    }

                    // ══════════════════════════════════════════════════
                    // Calculate salary components
                    // ══════════════════════════════════════════════════
                    // HR_PER is a multiplier: (100 + HR_PER) / 100
                    $v_hr_multiplier = (100 + $v_hr) / 100;

                    // MR_AMT (Medical Allowance)
                    $mr_amt = $v_mr;

                    // BASIC: round((gross - (mr_amt + convance + food)) / hr_multiplier)
                    $basic = $v_hr_multiplier > 0
                        ? round(($v_basic - ($v_mr + $v_convance + $v_food)) / $v_hr_multiplier)
                        : 0;

                    // HR_AMT: (gross - basic) - (mr_amt + convance + food)
                    $hr_amt = ($v_basic - $basic) - ($v_mr + $v_convance + $v_food);

                    // Convance and Food
                    $convance = $v_convance;
                    $food_allowance = $v_food;

                    // Tax amount
                    $tax_amt = 0;

                    // Stamp
                    $calc_stamp = $v_stamp;

                    // Exact EMP_PAYMENT column names from Oracle DDL
                    $rows[] = [
                        'empno'          => $emp->empno,
                        'new_empno'      => $emp->new_empno,
                        'company_id'     => $companyId,
                        'gross'          => $v_basic,
                        'basic'          => $basic,
                        'hr_rate'        => 0,
                        'mr_rate'        => 0,
                        'others_rate'    => 0,
                        'hr_amt'         => round($hr_amt, 2),
                        'mr_amt'         => round($mr_amt, 2),
                        'others_amt'     => 0,
                        'stamp'          => round($calc_stamp, 2),
                        'transport'      => 0,
                        'convance'       => round($convance, 2),
                        'grade'          => 0,
                        'section'        => null,
                        'des_name'       => $emp->des_name ?? null,
                        'atnd_bonus'     => 0,
                        'fest_bonus'     => 0,
                        'others'         => 0,
                        'ot_ent'         => 'No',
                        'ot_rate'        => 0,
                        'allowance'      => 'No',
                        'tax'            => $tax_amt,
                        'food_allowance' => round($food_allowance, 2),
                        'emp_grade'      => $emp->grade ?? null,
                    ];
                    $inserted++;
                } catch (\Exception $e) {
                    \Log::error("GROSS_ENTRY: Error processing employee {$emp->empno} - " . $e->getMessage());
                    throw $e;
                }
            }

            \Log::info("GROSS_ENTRY: Prepared " . count($rows) . " rows for insert");

            // Bulk insert
            try {
                DB::table('emp_payment')->insert($rows);
                \Log::info("GROSS_ENTRY: Successfully inserted " . count($rows) . " records");
            } catch (\Exception $e) {
                \Log::error("GROSS_ENTRY: Error inserting rows - " . $e->getMessage());
                throw $e;
            }

            DB::commit();

            // Return the full list (existing + newly inserted) - explicit columns
            try {
                $allRows = DB::table('emp_payment as ep')
                    ->leftJoin('emp_official as eo', function ($j) {
                        $j->on('ep.empno', '=', 'eo.empno')
                          ->on('ep.company_id', '=', 'eo.company_id');
                    })
                    ->where('ep.company_id', $companyId)
                    ->where('ep.ot_ent', 'No')
                    ->select(
                        'ep.empno', 'ep.new_empno', 'ep.gross', 'ep.basic',
                        'ep.hr_amt', 'ep.mr_amt', 'ep.others_amt',
                        'ep.stamp', 'ep.transport', 'ep.convance',
                        'ep.des_name', 'ep.atnd_bonus', 'ep.fest_bonus',
                        'ep.ot_ent', 'ep.tax', 'ep.food_allowance',
                        'ep.emp_grade', 'ep.company_id',
                        DB::raw("HRM.GET_EMP_NAME(ep.empno) AS emp_name"),
                        DB::raw("get_dept_name(eo.dept_no)  AS dept_name"),
                        DB::raw("get_section(eo.section_no) AS section_name")
                    )
                    ->orderBy('ep.empno')
                    ->get();
                
                \Log::info("GROSS_ENTRY: Fetched " . count($allRows) . " total records after insert");
            } catch (\Exception $e) {
                \Log::error("GROSS_ENTRY: Error fetching records after insert - " . $e->getMessage());
                throw $e;
            }

            return response()->json([
                'message'  => "{$inserted} employee(s) added to EMP_PAYMENT.",
                'inserted' => $inserted,
                'data'     => $allRows,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error("GROSS_ENTRY: Transaction failed - " . $e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
                'error' => $e->getLine() . ' - ' . $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
        
        } catch (\Exception $e) {
            \Log::error("GROSS_ENTRY: Unexpected error - " . $e->getMessage());
            return response()->json([
                'message' => 'Unexpected error: ' . $e->getMessage(),
                'error' => $e->getLine() . ' - ' . $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }
    /* ══════════════════════════════════════════════════════════════════
       STORE ENTRY  (manual new entry into EMP_PAYMENT)
       Mirrors: CTRL.PB_SAVE → Commit_Form
       SELECT COUNT(*) FROM EMP_PAYMENT WHERE EMPNO=:b1 AND COMPANY_ID=:b2
    ══════════════════════════════════════════════════════════════════ */
    public function storeEntry(Request $request): JsonResponse
    {
        $request->validate(['company_id' => 'required', 'empno' => 'required']);

        // Duplicate check
        $exists = DB::table('emp_payment')
            ->where('empno',      $request->empno)
            ->where('company_id', $request->company_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => "EMP_PAYMENT record already exists for Emp {$request->empno}. Use Edit to update."
            ], 422);
        }

        DB::beginTransaction();
        try {
            DB::table('emp_payment')->insert($this->buildEmpPaymentRow($request));
            DB::commit();
            return response()->json(['message' => "Entry saved for {$request->empno}."], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /* ══════════════════════════════════════════════════════════════════
       UPDATE ENTRY
    ══════════════════════════════════════════════════════════════════ */
    public function updateEntry(Request $request, string $empNo): JsonResponse
    {
        $request->validate(['company_id' => 'required']);

        DB::beginTransaction();
        try {
            DB::table('emp_payment')
                ->where('empno',      $empNo)
                ->where('company_id', $request->company_id)
                ->update($this->buildEmpPaymentRow($request));
            DB::commit();
            return response()->json(['message' => "Entry updated for {$empNo}."]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /* ══════════════════════════════════════════════════════════════════
       DELETE ENTRY  (remove from EMP_PAYMENT)
    ══════════════════════════════════════════════════════════════════ */
    public function destroyEntry(Request $request, string $empNo): JsonResponse
    {
        $request->validate(['company_id' => 'required']);

        DB::beginTransaction();
        try {
            $deleted = DB::table('emp_payment')
                ->where('empno',      $empNo)
                ->where('company_id', $request->company_id)
                ->delete();
            DB::commit();
            return response()->json(['message' => "{$deleted} entry deleted.", 'deleted' => $deleted]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /* ══════════════════════════════════════════════════════════════════
       GET DISTINCT PAYMENT DATES  (for Delete salary LOV)
       Mirrors: SELECT DISTINCT PAYMENT_DATE FROM SALARY_PAYMENT_INFO
                WHERE COMPANY_ID = :BLOCK3.COMPANY_ID
                ORDER BY PAYMENT_DATE DESC
    ══════════════════════════════════════════════════════════════════ */
    public function getDeleteDates(Request $request): JsonResponse
    {
        $request->validate(['company_id' => 'required']);

        $companyId = (int) $request->company_id;
        
        $rows = DB::select(
            "SELECT DISTINCT TO_CHAR(payment_date,'DD-Mon-YYYY') AS payment_date_display,
                            TRUNC(payment_date) AS payment_date
             FROM salary_payment_info 
             WHERE company_id = ? 
             ORDER BY payment_date DESC",
            [$companyId]
        );

        \Log::info("GET_DELETE_DATES: company_id={$companyId}, rows returned=" . count($rows));

        return response()->json(['data' => $rows, 'count' => count($rows)]);
    }

    /* ══════════════════════════════════════════════════════════════════
       COUNT SALARY RECORDS (preview before delete)
    ══════════════════════════════════════════════════════════════════ */
    public function countSalary(Request $request): JsonResponse
    {
        $request->validate(['company_id' => 'required']);

        $query = DB::table('salary_payment_info')
            ->where('company_id', $request->company_id);

        if ($request->filled('payment_date')) {
            $date = $this->parseDate($request->payment_date);
            if ($date) $query->whereDate('payment_date', $date->format('Y-m-d'));
        }

        return response()->json(['count' => $query->count()]);
    }

    /* ══════════════════════════════════════════════════════════════════
       DELETE SALARY PERIOD  (from SALARY_PAYMENT_INFO)
       Mirrors: BLOCK3.PB_DELETE / BTN_DELETE:
         DELETE FROM SALARY_PAYMENT_INFO
         WHERE PAYMENT_DATE = :BLOCK3.DELETE_DATE
         AND   COMPANY_ID   = :BLOCK3.COMPANY_ID
    ══════════════════════════════════════════════════════════════════ */
    public function deleteSalary(Request $request): JsonResponse
    {
        $request->validate(['company_id' => 'required', 'payment_date' => 'required']);

        $date = $this->parseDate($request->payment_date);
        if (!$date) {
            return response()->json(['message' => 'Invalid payment date.'], 422);
        }

        DB::beginTransaction();
        try {
            $deleted = DB::table('salary_payment_info')
                ->where('company_id', $request->company_id)
                ->whereDate('payment_date', $date->format('Y-m-d'))
                ->delete();

            DB::commit();
            return response()->json([
                'message' => "{$deleted} record(s) deleted for {$request->payment_date}.",
                'deleted' => $deleted,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /* ══════════════════════════════════════════════════════════════════
       PROCESS SALARY  (SALARY_PROCESSOR_worker.fmb – BTN_PROCESS)
       Mirrors Oracle:
         date1 := to_date(:DATE_FROM || :txt_m || '-' || year, 'DD-fmMonth-YYYY')
         date2 := to_date(:DATE_TO   || to_char(todays,'mon-yyyy'))
         sal_chk_if_salary_calc(date1, date2, :BLOCK3.COMPANY_ID)
       Reads EMP_PAYMENT → calculates → writes to SALARY_PAYMENT_INFO
    ══════════════════════════════════════════════════════════════════ */
    public function processSalary(Request $request): JsonResponse
    {
        $request->validate([
            'company_id' => 'required',
            'date_from'  => 'required',
            'date_to'    => 'required',
        ]);

        $date1 = $this->parseDate($request->date_from);
        $date2 = $this->parseDate($request->date_to);

        if (!$date1 || !$date2) {
            return response()->json([
                'message' => 'Invalid date format. Use dd-Mon-yyyy (e.g. 01-Jan-2026)'
            ], 422);
        }

        if ($date1->gt($date2)) {
            return response()->json(['message' => 'From date must be before To date.'], 422);
        }

        $companyId = (string) $request->company_id;
        $empType   = $request->emp_type ? (int) $request->emp_type : 1;
        $d1Str     = $date1->format('d-M-Y');  // Carbon format: 01-May-2026
        $d2Str     = $date2->format('d-M-Y');  // Carbon format: 31-May-2026

        \Log::info("PROCESS_SALARY: Calling SAL_MAIN with d1={$d1Str}, d2={$d2Str}, empType={$empType}, cid={$companyId}");

        try {
 DB::statement("
                ALTER SESSION SET NLS_DATE_FORMAT = 'DD-MON-YYYY'
            ");

            DB::statement(
                'BEGIN  SAL_MAIN(:p1, :p2, :p3, :p4); END;',
                ['p1' => $d1Str, 'p2' => $d2Str, 'p3' => $empType, 'p4' => $companyId]
            );

            \Log::info("PROCESS_SALARY: Successfully executed SAL_MAIN");

            return response()->json([
                'message' => "Salary processed: {$request->date_from} → {$request->date_to} | Company: {$companyId}",
            ]);

        } catch (\Exception $e) {
            \Log::error('PROCESS_SALARY: Error calling SAL_MAIN.SAL_CHK_IF_SALARY_CALC - ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /* ══════════════════════════════════════════════════════════════════
       GET EMPLOYEE INFO  (for entry form auto-fill)
       Mirrors: WHEN-VALIDATE-ITEM (EMP_PAYMENT.EMPNO)
    ══════════════════════════════════════════════════════════════════ */
    public function getEmpInfo(Request $request): JsonResponse
    {
        $request->validate(['emp_no' => 'required', 'company_id' => 'required']);

        $emp = DB::table('emp_official as eo')
            ->join('emp_personal as ep', 'eo.empno', '=', 'ep.empno')
            ->where('eo.empno',      $request->emp_no)
            ->where('eo.company_id', $request->company_id)
            ->select(
                'eo.empno', 'ep.new_empno',
                DB::raw("HRM.GET_EMP_NAME(eo.empno)    AS emp_name"),
                'eo.des_name',
                DB::raw("get_dept_name(eo.dept_no)     AS dept_name"),
                DB::raw("get_section(eo.section_no)    AS section_name"),
                'eo.emp_type', 'eo.gross',
                DB::raw("NULLIF(REPLACE(eo.grade_id,'Grade-',''), '') AS grade"),
                DB::raw("TO_CHAR(eo.joining_date,'DD-Mon-YYYY') AS joining_date")
            )
            ->first();

        if (!$emp) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        $advBalance = DB::table('emp_loan_master')
            ->where('emp_no',     $request->emp_no)
            ->where('company_id', $request->company_id)
            ->where('is_close',   'N')
            ->sum('pre_balance_amount') ?? 0;

        $data = (array) $emp;
        $data['advance_balance'] = $advBalance;

        return response()->json(['data' => $data]);
    }

    /* ══════════════════════════════════════════════════════════════════
       GET SALARY PARAMETERS
       Mirrors: WHEN-VALIDATE-ITEM (EMP_PAYMENT.GROSS)
         SELECT STAMP_AMT,HR_PER,MR_AMT,CONVANCE_AMT,FOOD_AMT
         FROM SALARY_PARAMETER
    ══════════════════════════════════════════════════════════════════ */
    public function getSalaryParams(Request $request): JsonResponse
    {
        $params = DB::table('salary_parameter')
            ->select('stamp_amt', 'hr_per', 'mr_amt', 'convance_amt', 'food_amt', 'pf_per')
            ->first();

        return response()->json(['data' => $params ?? (object) []]);
    }

    /* ══════════════════════════════════════════════════════════════════
       PRIVATE HELPERS
    ══════════════════════════════════════════════════════════════════ */
    private function parseDate(string $value): ?Carbon
    {
        foreach (['d-M-Y', 'Y-m-d', 'd-m-Y', 'j-M-Y', 'd-F-Y'] as $fmt) {
            try { return Carbon::createFromFormat($fmt, trim($value)); } catch (\Exception $e) {}
        }
        try { return Carbon::parse($value); } catch (\Exception $e) { return null; }
    }

    private function buildEmpPaymentRow(Request $request): array
    {
        // Exact EMP_PAYMENT column names from Oracle DDL
        return array_filter([
            'empno'          => $request->empno,
            'new_empno'      => $request->new_empno,
            'company_id'     => $request->company_id,
            'gross'          => $request->gross,
            'basic'          => $request->basic,        
            'hr_rate'        => $request->hr_rate,
            'mr_rate'        => $request->mr_rate,
            'others_rate'    => $request->others_rate,
            'hr_amt'         => $request->hr_amt,       
            'mr_amt'         => $request->mr_amt,       
            'others_amt'     => $request->others_amt,
            'stamp'          => $request->stamp,
            'transport'      => $request->transport,
            'convance'       => $request->convance,
            'grade'          => $request->grade,
            'section'        => $request->section,
            'des_name'       => $request->des_name,
            'atnd_bonus'     => $request->atnd_bonus,   
            'fest_bonus'     => $request->fest_bonus,
            'others'         => $request->others,
            'ot_ent'         => $request->ot_ent        ?? 'No',
            'ot_rate'        => $request->ot_rate,
            'allowance'      => $request->allowance     ?? 'No',
            'tax'            => $request->tax,
            'food_allowance' => $request->food_allowance, // FOOD_ALLOWANCE
            'emp_grade'      => $request->emp_grade,
        ], fn($v) => !is_null($v));
    }
}
