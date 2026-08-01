<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FestivalBonusController extends Controller
{
    // ─────────────────────────────────────────────
    //  Page entry-point
    // ─────────────────────────────────────────────
    public function index()
    {
        $religions  = $this->getReligions();
        $bonusTypes = $this->getBonusTypes();

        return view('payroll.festival_bonus.index', compact('religions', 'bonusTypes'));
    }

    // ─────────────────────────────────────────────
    //  Calculate & insert festival bonus
    // ─────────────────────────────────────────────
    public function calculate(Request $request)
    {
        try {
            $request->validate([
                'payment_date'  => 'required|date',
                'religion_id'   => 'nullable|integer',
                'bonus_type_id' => 'required|integer',
            ]);

            $paymentDate  = Carbon::parse($request->payment_date);
            $religionId   = $request->religion_id;
            $bonusTypeId  = $request->bonus_type_id;

            Log::info('Festival Bonus Calculation Started', [
                'payment_date'  => $paymentDate->format('Y-m-d'),
                'bonus_type_id' => $bonusTypeId,
            ]);

            $alreadyExists = DB::selectOne(
                "SELECT COUNT(*) AS cnt FROM festival_bonus WHERE TRUNC(payment_date) = TRUNC(TO_DATE(:pd,'YYYY-MM-DD'))",
                ['pd' => $paymentDate->format('Y-m-d')]
            );

            if ($alreadyExists && $alreadyExists->cnt > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bonus already calculated for this date.',
                ], 422);
            }

            $sql = "
                SELECT ep.empno,
                       ep.basic                bsc,
                       eo.joining_date         joining_date,
                       eo.gross                gross,
                       eo.work_ent             work_ent,
                       eo.emp_type             emp_type,
                       em.religion_id          religion_id
                FROM   emp_official  eo
                JOIN   emp_payment   ep ON ep.empno  = eo.empno
                JOIN   emp_personal  em ON em.empno  = ep.empno
                WHERE  em.status = 'Active'
                AND    em.religion_id = NVL(:religion_id, em.religion_id)
            ";

            $employees = DB::select($sql, ['religion_id' => $religionId]);

            $inserted = 0;
            $skipped  = 0;
            $noAttend = 0;

            foreach ($employees as $emp) {
                $joiningDate = Carbon::parse($emp->joining_date);
                $jobLength   = round($paymentDate->diffInMonths($joiningDate, true), 2);

                $bonusMaster = DB::selectOne("
                    SELECT D.BNOUS_PERCENT,
                           D.GROSS_BASIC
                    FROM   EMP_BONUS_MASTER M
                    JOIN   EMP_BNOUS_DETAILS D ON M.BNOUS_ID = D.BNOUS_ID
                    WHERE  M.BNOUS_TYPE  = :work_ent
                    AND    :job_length BETWEEN D.JOB_LENTH_FROM AND D.JOB_LENTH_TO
                ", [
                    'job_length' => $jobLength,
                    'work_ent'   => $emp->work_ent,
                ]);

                if (!$bonusMaster) { $skipped++; continue; }

                $percent    = $bonusMaster->bnous_percent ?? 0;
                $basicGross = $bonusMaster->gross_basic   ?? null;

                if ($basicGross === 'Gross') {
                    $bnsValue = round(($emp->gross * $percent) / 100);
                } elseif ($basicGross === 'Basic') {
                    $bnsValue = round(($emp->bsc   * $percent) / 100);
                } else {
                    $bnsValue = 0;
                }

                if (strtolower($emp->emp_type) === 'contractual') {
                    $attendPct = $this->getEmpAttendancePercent($emp->empno, $paymentDate);

                    if ($attendPct < 0) {
                        try {
                            DB::insert("
                                INSERT INTO festival_bonus_no_emp
                                    (empno, payment_date, basic, gross, job_length, joining_date, earn_percent, RELIGION_ID, BONUS_TYPE_ID)
                                VALUES
                                    (:empno, TO_DATE(:pd,'YYYY-MM-DD'), :bsc, :gross, :jl, TO_DATE(:jd,'YYYY-MM-DD'), :ep, :rid, :btid)
                            ", [
                                'empno' => $emp->empno, 'pd' => $paymentDate->format('Y-m-d'),
                                'bsc' => $emp->bsc, 'gross' => $emp->gross, 'jl' => $jobLength,
                                'jd' => $joiningDate->format('Y-m-d'), 'ep' => $attendPct,
                                'rid' => $religionId, 'btid' => $bonusTypeId,
                            ]);
                            $noAttend++;
                        } catch (\Exception $e) {
                            Log::error('Failed to insert no-attend record', ['empno' => $emp->empno, 'error' => $e->getMessage()]);
                            $noAttend++;
                        }
                    } elseif ($bnsValue >= 1) {
                        try {
                            DB::insert("
                                INSERT INTO festival_bonus
                                    (empno, payment_date, bonus_amount, basic, gross, job_length, joining_date, PERCENT, RELIGION_ID, BONUS_TYPE_ID)
                                VALUES
                                    (:empno, TO_DATE(:pd,'YYYY-MM-DD'), :amt, :bsc, :gross, :jl, TO_DATE(:jd,'YYYY-MM-DD'), :pct, :rid, :btid)
                            ", [
                                'empno' => $emp->empno, 'pd' => $paymentDate->format('Y-m-d'),
                                'amt' => $bnsValue, 'bsc' => $emp->bsc, 'gross' => $emp->gross,
                                'jl' => $jobLength, 'jd' => $joiningDate->format('Y-m-d'),
                                'pct' => $percent, 'rid' => $religionId, 'btid' => $bonusTypeId,
                            ]);
                            $inserted++;
                        } catch (\Exception $e) {
                            Log::error('Failed to insert bonus (contractual)', ['empno' => $emp->empno, 'error' => $e->getMessage()]);
                            $skipped++;
                        }
                    } else {
                        $skipped++;
                    }
                } else {
                    if ($bnsValue >= 1) {
                        try {
                            DB::insert("
                                INSERT INTO festival_bonus
                                    (empno, payment_date, bonus_amount, basic, gross, job_length, joining_date, PERCENT, RELIGION_ID, BONUS_TYPE_ID)
                                VALUES
                                    (:empno, TO_DATE(:pd,'YYYY-MM-DD'), :amt, :bsc, :gross, :jl, TO_DATE(:jd,'YYYY-MM-DD'), :pct, :rid, :btid)
                            ", [
                                'empno' => $emp->empno, 'pd' => $paymentDate->format('Y-m-d'),
                                'amt' => $bnsValue, 'bsc' => $emp->bsc, 'gross' => $emp->gross,
                                'jl' => $jobLength, 'jd' => $joiningDate->format('Y-m-d'),
                                'pct' => $percent, 'rid' => $religionId, 'btid' => $bonusTypeId,
                            ]);
                            $inserted++;
                        } catch (\Exception $e) {
                            Log::error('Failed to insert bonus (regular)', ['empno' => $emp->empno, 'error' => $e->getMessage()]);
                            $skipped++;
                        }
                    } else {
                        $skipped++;
                    }
                }
            }

            Log::info('Festival Bonus Calculation Completed', [
                'inserted' => $inserted, 'skipped' => $skipped, 'no_attend' => $noAttend,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Operation Completed. Inserted: {$inserted}, Skipped: {$skipped}, No-Attend: {$noAttend}.",
                'details' => [
                    'total_employees_processed' => count($employees),
                    'inserted'           => $inserted,
                    'skipped_low_bonus'  => $skipped,
                    'no_attendance'      => $noAttend,
                    'success_rate'       => count($employees) > 0
                        ? round(($inserted / count($employees)) * 100, 2) : 0,
                ],
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected error in Festival Bonus Calculation', [
                'error' => $e->getMessage(), 'line' => $e->getLine(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please check the logs.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────
    //  Get last batch info — used by delete modal
    // ─────────────────────────────────────────────
    public function lastBatch()
    {
        $row = DB::selectOne("
            SELECT TO_CHAR(MAX(payment_date), 'DD-Mon-YYYY') AS last_date_fmt,
                   MAX(payment_date)                          AS last_date_raw,
                   COUNT(*)                                   AS total_records
            FROM   festival_bonus
            WHERE  TRUNC(payment_date) = (
                SELECT TRUNC(MAX(payment_date)) FROM festival_bonus
            )
        ");

        if (!$row || !$row->last_date_fmt) {
            return response()->json(['success' => false, 'message' => 'No records found.']);
        }

        return response()->json([
            'success'       => true,
            'last_date_fmt' => $row->last_date_fmt,
            'total_records' => $row->total_records,
        ]);
    }

    // ─────────────────────────────────────────────
    //  Delete last payment batch
    // ─────────────────────────────────────────────
    public function deleteLast()
    {
        try {
            $lastDate = DB::selectOne(
                "SELECT MAX(payment_date) AS last_date FROM festival_bonus"
            );

            if (!$lastDate || !$lastDate->last_date) {
                return response()->json([
                    'success' => false,
                    'message' => 'No records to delete.',
                ], 422);
            }

            // Convert to string first — Oracle PDO can't bind a DATE column directly to TRUNC()
            $pdStr = Carbon::parse($lastDate->last_date)->format('Y-m-d');

            $deleted = DB::delete(
                "DELETE FROM festival_bonus WHERE TRUNC(payment_date) = TRUNC(TO_DATE(:pd, 'YYYY-MM-DD'))",
                ['pd' => $pdStr]
            );

            Log::info('Festival Bonus last batch deleted', [
                'payment_date'   => $lastDate->last_date,
                'rows_deleted'   => $deleted,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Last bonus batch deleted successfully. ({$deleted} records removed)",
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete bonus batch', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────
    //  Preview — server-side filter + pagination
    //  GET params: payment_date, empno, emp_name,
    //              per_page (default 25), page (default 1)
    // ─────────────────────────────────────────────
   public function preview(Request $request)
{
    $request->validate([
        'payment_date' => 'required|date'
    ]);

    $pd      = Carbon::parse($request->payment_date)->format('Y-m-d');
    $empno   = trim($request->input('empno', ''));
    $empName = trim($request->input('emp_name', ''));
    $perPage = max(1, (int) $request->input('per_page', 25));
    $page    = max(1, (int) $request->input('page', 1));
    $offset  = ($page - 1) * $perPage;

    // Dynamic WHERE conditions
    $whereParts = [
        "TRUNC(fb.payment_date) = TRUNC(TO_DATE(:pd,'YYYY-MM-DD'))"
    ];

    $bindings = [
        'pd' => $pd
    ];

    if ($empno !== '') {
        $whereParts[] = "UPPER(TO_CHAR(fb.empno)) LIKE UPPER(:empno)";
        $bindings['empno'] = '%' . $empno . '%';
    }

    if ($empName !== '') {
        $whereParts[] = "UPPER(ep.first_name || ' ' || ep.last_name) LIKE UPPER(:emp_name)";
        $bindings['emp_name'] = '%' . $empName . '%';
    }

    $whereClause = implode(' AND ', $whereParts);

    // Total count
    $countSql = "
        SELECT COUNT(*) AS total_count
        FROM festival_bonus fb
        LEFT JOIN emp_personal ep
            ON ep.empno = fb.empno
        LEFT JOIN bonus_type bt
            ON bt.bonus_id = fb.bonus_type_id
        LEFT JOIN religion r
            ON r.religion_id = fb.religion_id
        WHERE {$whereClause}
    ";

    $countRow   = DB::selectOne($countSql, $bindings);
    $totalCount = (int) ($countRow->total_count ?? 0);

    // Summary totals
    $sumSql = "
        SELECT
            NVL(SUM(fb.bonus_amount), 0) AS grand_total,
            NVL(AVG(fb.bonus_amount), 0) AS grand_avg
        FROM festival_bonus fb
        LEFT JOIN emp_personal ep
            ON ep.empno = fb.empno
        LEFT JOIN bonus_type bt
            ON bt.bonus_id = fb.bonus_type_id
        LEFT JOIN religion r
            ON r.religion_id = fb.religion_id
        WHERE {$whereClause}
    ";

    $sumRow = DB::selectOne($sumSql, $bindings);

    // Pagination for Oracle 11g
    $startRow = $offset + 1;
    $endRow   = $offset + $perPage;

    $dataSql = "
        SELECT *
        FROM (
            SELECT
                fb.empno,
                ep.first_name || ' ' || ep.last_name AS emp_name,
                fb.basic,
                fb.gross,
                fb.bonus_amount,
                fb.PERCENT,
                TO_CHAR(fb.joining_date,'DD-Mon-YYYY') AS joining_date,
                ROUND(fb.job_length, 1) AS job_months,
                bt.bonus_name AS bonus_type,
                r.religion_name,
                ROW_NUMBER() OVER (ORDER BY fb.empno) AS rn
            FROM festival_bonus fb
            LEFT JOIN emp_personal ep
                ON ep.empno = fb.empno
            LEFT JOIN bonus_type bt
                ON bt.bonus_id = fb.bonus_type_id
            LEFT JOIN religion r
                ON r.religion_id = fb.religion_id
            WHERE {$whereClause}
        )
        WHERE rn BETWEEN :start_row AND :end_row
        ORDER BY rn
    ";

    $dataBindings = $bindings;
    $dataBindings['start_row'] = $startRow;
    $dataBindings['end_row']   = $endRow;

    $rows = DB::select($dataSql, $dataBindings);

    return response()->json([
        'success' => true,
        'data'    => $rows,
        'meta'    => [
            'total'       => $totalCount,
            'per_page'    => $perPage,
            'page'        => $page,
            'last_page'   => $totalCount > 0
                ? (int) ceil($totalCount / $perPage)
                : 1,
            'from'        => $totalCount > 0
                ? $startRow
                : 0,
            'to'          => min($endRow, $totalCount),
            'grand_total' => round((float) ($sumRow->grand_total ?? 0), 2),
            'grand_avg'   => round((float) ($sumRow->grand_avg ?? 0), 2),
        ],
    ]);
}

    // ─────────────────────────────────────────────
    //  Private helpers
    // ─────────────────────────────────────────────
    private function getReligions(): array
    {
        return DB::select("SELECT RELIGION_NAME, RELIGION_ID FROM religion ORDER BY RELIGION_ID ASC");
    }

    private function getBonusTypes(): array
    {
        return DB::select("SELECT BONUS_NAME, BONUS_ID FROM bonus_type ORDER BY BONUS_ID ASC");
    }

    private function getEmpAttendancePercent(string $empno, Carbon $paymentDate): float
    {
        $result = DB::selectOne("
            SELECT GET_EMP_ATTENDACE_PERCENT(:empno, TO_DATE(:pd,'YYYY-MM-DD')) AS pct FROM DUAL
        ", ['empno' => $empno, 'pd' => $paymentDate->format('Y-m-d')]);

        return (float) ($result->pct ?? -1);
    }
}
