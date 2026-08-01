<?php

namespace App\Http\Controllers\HrmModule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AttendanceChangeController extends Controller
{
    // ─── View ────────────────────────────────────────────────────────────────

    public function index()
    {
        return view('hrm.attendance_change.index');
    }

    // ─── LOV: Company (permission-filtered) ──────────────────────────────────

    public function getCompanies()
    {
$userId = strtoupper(Auth::user()->user_id ?: Auth::user()->user_id);
         $rows = DB::select("
            SELECT COMPANY_NAME AS text, COMPANY_ID AS id
            FROM COMPANY_PROFILE
            WHERE  COMPANY_ID IN (
                  SELECT COMPANY_ID FROM COMPANY_PERMISSION
                  WHERE USER_GROUP_ID IN (
                      SELECT P.USER_GROUP_ID
                      FROM USER_PERMISSION P
                      JOIN AUTH_GROUP A ON A.USER_GROUP_ID = P.USER_GROUP_ID
                      WHERE P.USER_ID  = :USER_ID
                        AND A.GROUP_TYEP = 'U'
                  )
              )
            ORDER BY COMPANY_NAME
        ", ['USER_ID' => $userId]);

        return response()->json($rows);
    }

    // ─── Search / Query ───────────────────────────────────────────────────────

    public function search(Request $request)
    {   $request->validate([
            'from_date'  => 'required',
            'to_date'    => 'required',
            'company_id' => 'required',
        ]);

        // YYYY-MM-DD avoids NLS_DATE_LANGUAGE issues with MON format
        $fromDate  = \Carbon\Carbon::parse($request->from_date)->format('Y-m-d');
        $toDate    = \Carbon\Carbon::parse($request->to_date)->format('Y-m-d');
        $companyId = $request->company_id;
        $empno     = trim($request->empno     ?? '');
        $empnoNew  = trim($request->empno_new ?? '');
        $status    = trim($request->status    ?? '');
        $status2   = trim($request->status2   ?? '');

        // Prefixed bind names avoid Oracle duplicate-bind errors
        $where  = "WHERE AD.COMPANY_ID = :p_company
                     AND AD.ATT_DATE BETWEEN TO_DATE(:p_from,'YYYY-MM-DD')
                                         AND TO_DATE(:p_to,  'YYYY-MM-DD')";
        $params = [
            'p_company' => $companyId,
            'p_from'    => $fromDate,
            'p_to'      => $toDate,
        ];

        if ($empno !== '') {
            $where .= " AND AD.EMPNO = :p_empno";
            $params['p_empno'] = $empno;
        }
        if ($empnoNew !== '') {
            $where .= " AND AD.EMPNO_NEW LIKE :p_empno_new";
            $params['p_empno_new'] = '%' . $empnoNew . '%';
        }
        if ($status !== '') {
            // NVL so NULL column values can be matched with literal 'NULL'
            $where .= " AND NVL(UPPER(TRIM(AD.STATUS)), 'NULL') = UPPER(TRIM(:p_status))";
            $params['p_status'] = $status;
        }
        if ($status2 !== '') {
            $where .= " AND NVL(UPPER(TRIM(AD.STATUS2)), 'NULL') = UPPER(TRIM(:p_status2))";
            $params['p_status2'] = $status2;
        }

        try {
            $rows = DB::select("
                SELECT
                    AD.EMPNO,
                    AD.EMPNO_NEW,
                    TO_CHAR(AD.ATT_DATE,'DD-MON-YYYY')  AS ATT_DATE,
                    TO_CHAR(AD.IN_TIME, 'HH24:MI')      AS IN_TIME,
                    TO_CHAR(AD.IN_TIME2,'HH24:MI')      AS IN_TIME2,
                    TO_CHAR(AD.OUT_TIME,'HH24:MI')      AS OUT_TIME,
                    TO_CHAR(AD.OUT_TIME2,'HH24:MI')     AS OUT_TIME2,
                    TO_CHAR(AD.OUT_TIME3,'HH24:MI')     AS OUT_TIME3,
                    AD.OTHOUR, AD.OTHOUR2, AD.OTHOUR3,
                    AD.LATE,   AD.LATE2,   AD.LATE_EXTRA, AD.EXTRAOT,
                    AD.STATUS, AD.STATUS2,
                    AD.REMARKS, AD.LAT_REMARKS, AD.MANUAL_ATT,
                    AD.COMPANY_ID
                FROM ATTENDANCE_DETAILS AD
                $where
                ORDER BY AD.ATT_DATE DESC, AD.EMPNO_NEW ASC
            ", $params);

            return response()->json(
                array_map(fn($r) => array_change_key_case((array)$r, CASE_LOWER), $rows)
            );

        } catch (\Exception $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->getMessage(),
                'where'   => $where,
                'params'  => $params,
            ], 500);
        }
    }

    // ─── Save all rows ────────────────────────────────────────────────────────

    public function save(Request $request)
    {
        $request->validate(['rows' => 'required|array|min:1']);

        $userName = strtoupper(Auth::user()->oracle_user ?? Auth::user()->username);

        DB::transaction(function () use ($request, $userName) {
            foreach ($request->rows as $row) {
                // Normalise times server-side before building SQL expressions
                $inTime   = $this->timeExpr($row['in_time']   ?? '');
                $inTime2  = $this->timeExpr($row['in_time2']  ?? '');
                $outTime  = $this->timeExpr($row['out_time']  ?? '');
                $outTime2 = $this->timeExpr($row['out_time2'] ?? '');
                $outTime3 = $this->timeExpr($row['out_time3'] ?? '');

                DB::statement("
                    UPDATE ATTENDANCE_DETAILS SET
                        IN_TIME     = $inTime,
                        IN_TIME2    = $inTime2,
                        OUT_TIME    = $outTime,
                        OUT_TIME2   = $outTime2,
                        OUT_TIME3   = $outTime3,
                        OTHOUR      = :othour,
                        OTHOUR2     = :othour2,
                        OTHOUR3     = :othour3,
                        LATE        = :late,
                        LATE2       = :late2,
                        LATE_EXTRA  = :late_extra,
                        EXTRAOT     = :extraot,
                        REMARKS     = :remarks,
                        LAT_REMARKS = :lat_remarks,
                        MANUAL_ATT  = :manual_att,
                        STATUS2     = :status2
                    WHERE EMPNO_NEW  = :empno_new
                      AND ATT_DATE   = TO_DATE(:att_date,'DD-MON-YYYY')
                      AND COMPANY_ID = :company_id
                ", [
                    'othour'      => $row['othour']      ?: null,
                    'othour2'     => $row['othour2']     ?: null,
                    'othour3'     => $row['othour3']     ?: null,
                    'late'        => $row['late']        ?: null,
                    'late2'       => $row['late2']       ?: null,
                    'late_extra'  => $row['late_extra']  ?: null,
                    'extraot'     => $row['extraot']     ?: null,
                    'remarks'     => $row['remarks']     ?: null,
                    'lat_remarks' => $row['lat_remarks'] ?: null,
                    'manual_att'  => $row['manual_att']  ?: null,
                    'status2'     => $row['status2']     ?? 'P',
                    'empno_new'   => $row['empno_new'],
                    'att_date'    => strtoupper($row['att_date']),
                    'company_id'  => $row['company_id'],
                ]);

                // POST-UPDATE log
                DB::statement("
                    INSERT INTO TRACE_OPERATION (USER_NAME, WORK_DATE, EMPNO)
                    VALUES (:user_name, SYSDATE, :empno)
                ", ['user_name' => $userName, 'empno' => $row['empno_new']]);
            }
        });

        return response()->json(['success' => true, 'message' => count($request->rows) . ' record(s) saved successfully.']);
    }


    // ─── Insert new rows ──────────────────────────────────────────────────────

    // ── Helpers ───────────────────────────────────────────────────────────────

    /** Normalise HH:MM — accepts 9, 09, 9:00, 9.3 → HH:MM or null */
    private function normaliseTime(?string $raw): ?string
    {
        $raw = trim($raw ?? '');
        if ($raw === '') return null;
        // Already HH:MM
        if (preg_match('/^\d{2}:\d{2}$/', $raw)) return $raw;
        // Separator (dot or colon): 9:3 → 09:30, 18.3 → 18:30, 9:30 → 09:30
        if (preg_match('/^(\d{1,2})[\.:](\d{1,2})$/', $raw, $m)) {
            $h  = (int)$m[1];
            $mn = strlen($m[2]) === 1 ? (int)$m[2] * 10 : (int)$m[2];
            if ($h <= 23 && $mn <= 59) return sprintf('%02d:%02d', $h, $mn);
        }
        // Pure digits: 9 → 09:00, 18 → 18:00, 930 → 09:30, 1830 → 18:30
        $d = preg_replace('/\D/', '', $raw);
        if (strlen($d) <= 2) {
            $h = (int)$d; if ($h <= 23) return sprintf('%02d:00', $h);
        }
        if (strlen($d) === 3) {
            $h = (int)$d[0]; $mn = (int)substr($d,1);
            if ($h <= 23 && $mn <= 59) return sprintf('%02d:%02d', $h, $mn);
        }
        if (strlen($d) === 4) {
            $h = (int)substr($d,0,2); $mn = (int)substr($d,2);
            if ($h <= 23 && $mn <= 59) return sprintf('%02d:%02d', $h, $mn);
        }
        return null; // unparseable
    }

    /** Normalise date string to DD-MON-YYYY (Oracle-safe, English month) */
    private function normaliseDate(?string $raw): ?string
    {
        $raw = strtoupper(trim($raw ?? ''));
        if ($raw === '') return null;
        $months = ['01'=>'JAN','02'=>'FEB','03'=>'MAR','04'=>'APR',
                   '05'=>'MAY','06'=>'JUN','07'=>'JUL','08'=>'AUG',
                   '09'=>'SEP','10'=>'OCT','11'=>'NOV','12'=>'DEC'];
        // Already DD-MON-YYYY
        if (preg_match('/^(\d{2})-([A-Z]{3})-(\d{4})$/', $raw, $m)) return $raw;
        // Separator formats: DD-MM-YYYY, DD/MM/YYYY, DD.MM.YYYY, DD-MM-YY
        if (preg_match('/^(\d{1,2})[-\/\.](\d{1,2})[-\/\.](\d{2,4})$/', $raw, $m)) {
            $d = (int)$m[1]; $mo = sprintf('%02d', (int)$m[2]);
            $y = strlen($m[3]) === 2 ? ((int)$m[3] <= 50 ? 2000+(int)$m[3] : 1900+(int)$m[3]) : (int)$m[3];
            if (!isset($months[$mo])) return null;
            return sprintf('%02d-%s-%04d', $d, $months[$mo], $y);
        }
        // Pure digits
        $d = preg_replace('/\D/', '', $raw);
        if (strlen($d) === 6) { // DDMMYY
            $dd=$d[0].$d[1]; $mm=$d[2].$d[3]; $yy=(int)substr($d,4);
            $y = $yy<=50?2000+$yy:1900+$yy;
            if (!isset($months[$mm])) return null;
            return sprintf('%s-%s-%04d', $dd, $months[$mm], $y);
        }
        if (strlen($d) === 7) { // DMMYYYY
            $dd=sprintf('%02d',(int)$d[0]); $mm=sprintf('%02d',(int)substr($d,1,2)); $y=substr($d,3);
            if (!isset($months[$mm])) return null;
            return sprintf('%s-%s-%s', $dd, $months[$mm], $y);
        }
        if (strlen($d) === 8) { // DDMMYYYY
            $dd=substr($d,0,2); $mm=substr($d,2,2); $y=substr($d,4);
            if (!isset($months[$mm])) return null;
            return sprintf('%s-%s-%s', $dd, $months[$mm], $y);
        }
        return null;
    }

    /** Build Oracle TO_DATE expression for time column */
    private function timeExpr(?string $raw): string
    {
        $t = $this->normaliseTime($raw);
        return $t ? "TO_DATE('01-JAN-1900 {$t}','DD-MON-YYYY HH24:MI')" : 'NULL';
    }

    // ─── Insert new rows ──────────────────────────────────────────────────────

    public function insert(Request $request)
    {
        $request->validate(['rows' => 'required|array|min:1']);

        $userName = strtoupper(Auth::user()->oracle_user ?? Auth::user()->user_id);

        DB::transaction(function () use ($request, $userName) {
            foreach ($request->rows as $row) {
                // Guard required fields
                if (empty($row['company_id'])) {
                    throw new \Exception('company_id is required on every row.');
                }
                if (empty($row['empno_new']) && empty($row['empno'])) {
                    throw new \Exception('Emp No / New Emp No is required.');
                }
                // Normalise date & times server-side — protects against un-blurred inputs
                $attDate = $this->normaliseDate($row['att_date'] ?? '');
                if (!$attDate) {
                    throw new \Exception('Invalid date value: ' . ($row['att_date'] ?? '(empty)'));
                }
                $inTime   = $this->timeExpr($row['in_time']   ?? '');
                $inTime2  = $this->timeExpr($row['in_time2']  ?? '');
                $outTime  = $this->timeExpr($row['out_time']  ?? '');
                $outTime2 = $this->timeExpr($row['out_time2'] ?? '');
                $outTime3 = $this->timeExpr($row['out_time3'] ?? '');

                DB::statement("
                    INSERT INTO ATTENDANCE_DETAILS (
                        EMPNO, EMPNO_NEW, ATT_DATE,
                        IN_TIME, IN_TIME2, OUT_TIME, OUT_TIME2, OUT_TIME3,
                        OTHOUR, OTHOUR2, OTHOUR3,
                        LATE, LATE2, LATE_EXTRA, EXTRAOT,
                        STATUS, STATUS2,
                        REMARKS, LAT_REMARKS, MANUAL_ATT,
                        COMPANY_ID
                    ) VALUES (
                        :empno, :empno_new, TO_DATE(:att_date,'DD-MON-YYYY'),
                        $inTime, $inTime2, $outTime, $outTime2, $outTime3,
                        :othour, :othour2, :othour3,
                        :late, :late2, :late_extra, :extraot,
                        :status, :status2,
                        :remarks, :lat_remarks, :manual_att,
                        :company_id
                    )
                ", [
                    'empno'       => $row['empno']      ?? $row['empno_new'] ?? null,
                    'empno_new'   => $row['empno_new']  ?? null,
                    'att_date'    => $attDate,
                    'othour'      => $row['othour']      ?: null,
                    'othour2'     => $row['othour2']     ?: null,
                    'othour3'     => $row['othour3']     ?: null,
                    'late'        => $row['late']        ?: null,
                    'late2'       => $row['late2']       ?: null,
                    'late_extra'  => $row['late_extra']  ?: null,
                    'extraot'     => $row['extraot']     ?: null,
                    'status'      => $row['status']      ?: null,
                    'status2'     => $row['status2']     ?: null,
                    'remarks'     => $row['remarks']     ?: null,
                    'lat_remarks' => $row['lat_remarks'] ?: null,
                    'manual_att'  => $row['manual_att']  ?: null,
                    'company_id'  => $row['company_id'],
                ]);

                DB::statement("
                    INSERT INTO TRACE_OPERATION (USER_NAME, WORK_DATE, EMPNO)
                    VALUES (:user_name, SYSDATE, :empno)
                ", ['user_name' => $userName, 'empno' => $row['empno_new'] ?? $row['empno']]);
            }
        });

        return response()->json(['success' => true, 'message' => count($request->rows) . ' record(s) inserted.']);
    }
    // ─── Delete rows ──────────────────────────────────────────────────────────

    public function delete(Request $request)
    {
        $request->validate(['rows' => 'required|array|min:1']);

        DB::transaction(function () use ($request) {
            foreach ($request->rows as $row) {
                DB::statement("
                    DELETE FROM ATTENDANCE_DETAILS
                    WHERE EMPNO_NEW  = :empno_new
                      AND ATT_DATE   = TO_DATE(:att_date,'DD-MON-YYYY')
                      AND COMPANY_ID = :company_id
                ", [
                    'empno_new'  => $row['empno_new'],
                    'att_date'   => strtoupper($row['att_date']),
                    'company_id' => $row['company_id'],
                ]);
            }
        });

        return response()->json(['success' => true, 'message' => count($request->rows) . ' record(s) deleted.']);
    }

    // ─── Set OUT_TIME3 via stored procedure ───────────────────────────────────

    public function setOutTime3(Request $request)
    {
        $request->validate([
            'from_date'  => 'required',
            'to_date'    => 'required',
            'company_id' => 'required',
        ]);

        $fromDate  = \Carbon\Carbon::parse($request->from_date)->format('Y-m-d');
        $toDate    = \Carbon\Carbon::parse($request->to_date)->format('Y-m-d');
        $companyId = $request->company_id;

        $rows = DB::select("
            SELECT DISTINCT EMPNO_NEW AS EMPNO, ATT_DATE, COMPANY_ID
            FROM ATTENDANCE_DETAILS
            WHERE OUT_TIME3  IS NULL
              AND STATUS      = 'P'
              AND ATT_DATE BETWEEN TO_DATE(:from_date,'YYYY-MM-DD')
                               AND TO_DATE(:to_date,'YYYY-MM-DD')
              AND COMPANY_ID  = :company_id
        ", ['from_date' => $fromDate, 'to_date' => $toDate, 'company_id' => $companyId]);

        $count = 0;
        DB::transaction(function () use ($rows, &$count) {
            foreach ($rows as $row) {
                DB::statement("BEGIN TAT_SET_OUT_TIME_3_BK(:empno, :att_date, '100'); END;", [
                    'empno'    => $row->empno,
                    'att_date' => $row->att_date,
                ]);
                $count++;
            }
        });

        return response()->json(['success' => true, 'message' => "OUT_TIME3 set for {$count} record(s)."]);
    }
}
