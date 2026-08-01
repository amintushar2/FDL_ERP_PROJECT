<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;


class LoginController extends BaseController
{
    // ─────────────────────────────────────────
    //  LOGIN PAGE
    // ─────────────────────────────────────────
    public function login()
    {
      try {
        DB::connection()->getPdo();
    } catch (PDOException $e) {
        dd($e);
        return response()->view('errors.db', [
            'message' => 'Database connection failed (Oracle)' . $e 
        ], 500);
    } catch (\Exception $e) {
        return response()->view('errors.db', [
            'message' => 'System unavailable' .$e 
        ], 500);
    }

    if (session('LoggedUser')) {
        return redirect('dashboard');
    }

    return view('auth.loginuser');
    }

    // ─────────────────────────────────────────
    //  AUTHENTICATE
    // ─────────────────────────────────────────
       public function check(Request $request)
    {
        $request->validate([
            'user_id'          => 'required',
            'initial_password' => 'required',
        ]);

        $oracleUser = Str::upper($request->user_id);
        $oraclePass = $request->initial_password;

        // ── Step 1: Validate credentials against Oracle ──────────────
        if (!$this->validateOracleCredentials($oracleUser, $oraclePass)) {
            return back()->with('fail', 'Invalid UserName or Password');
        }

        // ── Step 2: Load app user profile from User model ─────────────
        $user = User::where('user_id', $oracleUser)->first();

        if (!$user) {
            return back()->with('fail', 'User account not found in the system');
        }elseif($user->user_status == 'L'){
                        return back()->with('fail', 'User account is Locked . Please Contact Administrator');

        }
        // ── Step 3: Log in & set session ──────────────────────────────
        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        session([
            'LoggedUser' => $user->employee_id,
            'LoggedId'   => $user->user_id,
        ]);

        return redirect('/dashboard');
    }

    // ─────────────────────────────────────────
    //  ORACLE CREDENTIAL VALIDATOR
    //  Attempts a real Oracle connection using
    //  the supplied username & password.
    //  Returns true on success, false on failure.
    // ─────────────────────────────────────────
    private function validateOracleCredentials(string $username, string $password): bool
    {
        // Pull base config from your default Oracle connection
        $baseConfig = config('database.connections.oracle'); // adjust key if needed

        $testConfig = array_merge($baseConfig, [
            'username' => $username,
            'password' => $password,
            // Give it a unique name so it doesn't collide with the default connection
            'driver'   => $baseConfig['driver'] ?? 'oracle',
        ]);

        // Register a temporary connection
        config(['database.connections.oracle_auth_test' => $testConfig]);

        try {
            $pdo = DB::connection('oracle_auth_test')->getPdo();

            // Optionally confirm the schema user exists and is not locked
            // SELECT 1 FROM dual is the lightest possible Oracle query
            DB::connection('oracle_auth_test')->select('SELECT 1 FROM dual');

            return true;
        } catch (\Exception $e) {
            // ORA-01017 = invalid username/password
            // ORA-28000 = account locked
            // Any exception means auth failed
            \Log::warning('Oracle auth failed for user: ' . $username, [
                'error' => $e->getMessage(),
            ]);
            return false;
        } finally {
            // Always clean up the temporary connection
            DB::purge('oracle_auth_test');
            config(['database.connections.oracle_auth_test' => null]);
        }
    }

    // ─────────────────────────────────────────
    //  DASHBOARD
    //  Sidebar data is now shared automatically
    //  by BaseController — no need to re-query.
    // ─────────────────────────────────────────
    public function dashboard()
    {
        if (!session('LoggedUser')) {
            return redirect('login');
        }

        // Dashboard-specific queries only
        $wmpCountData = DB::table(DB::raw('HRM.EMP_PERSONAL Ep'))
            ->select(DB::raw('COUNT(EO.EMPNO) EMPNO'), 'EO.DEPT_NAME')
            ->crossJoin(DB::raw('HRM.EMP_OFFICIAL EO'))
            ->whereRaw('EO.EMPNO = Ep.EMPNO')
            ->where('Ep.STATUS', '=', 'Active')
            ->where('EO.COMPANY_ID', '=', '100')
            ->groupBy('EO.DEPT_NAME')
            ->get();

        $dailyOT = DB::table('ATTD_OT_GROUP_VW')->get();

        $dailyAttdSum = DB::table(DB::raw('attendance_details ad'))
            ->select('EP.SEX', DB::raw('COUNT(eo.empno) total_emp'))
            ->crossJoin(DB::raw('emp_official eo'))
            ->crossJoin(DB::raw('emp_personal ep'))
            ->whereRaw('eo.empno = ad.empno')
            ->whereRaw('ep.empno = eo.empno')
            ->where('AD.ATT_DATE', '=', '2023-04-08')
            ->where('eo.company_id', '=', '100')
            ->groupBy(['eo.company_id', 'EP.SEX'])
            ->get();

        $empCountData = '';
        foreach ($wmpCountData as $row) {
            $empCountData .= "['{$row->dept_name}',{$row->empno}],";
        }

        $empOTData = '';
        foreach ($dailyOT as $row) {
            $empOTData .= "['{$row->a_date}',{$row->c_hour}],";
        }

        $empATTDData = '';
        foreach ($dailyAttdSum as $row) {
            $empATTDData .= "['{$row->sex}',{$row->total_emp}],";
        }

        return view('dashboard', [
            'empATTDData'  => $empATTDData,
            'empOTData'    => $empOTData,
            'empCountData' => $empCountData,
            'expusd'       => '',
        ]);
        // NOTE: $data, $menu, $submenu, $submenu2, $headeer
        // are already shared by BaseController — no need to pass them here.
    }

    // ─────────────────────────────────────────
    //  LOGOUT
    // ─────────────────────────────────────────
    public function logout()
    {
        session()->pull('LoggedUser');
        session()->pull('LoggedId');
        Auth::logout();
        return redirect('login');
    }


   
public function liveData(Request $request)
{
    if (!session('LoggedUser')) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }
 
    try {
        $companyId = session('company_id', '100');

        // ── KPI: Total Active Employees ──────────────────────────────────────
        $totalEmployees =DB::table('HRM.EMP_OFFICIAL as eo')
    ->join('HRM.EMP_PERSONAL as ep', 'ep.EMPNO', '=', 'eo.EMPNO')
    ->where('eo.COMPANY_ID', $companyId)
    ->whereNull('eo.TERMINATION_DATE')
    ->whereNull('eo.RESIGNED_DATE')
    ->where('ep.STATUS', 'Active')
    ->count();
 
        // ── KPI: Today's Attendance Summary ─────────────────────────────────

        // ── UI DATE (for display) ──
        $today = Carbon::today();

        // ── EFFECTIVE WORKING DATE (skip Friday + holidays) ──
        $effectiveDate = DB::select("
            SELECT MAX(dt) AS dt
            FROM (
                SELECT TRUNC(SYSDATE) - LEVEL + 1 AS dt
                FROM DUAL
                CONNECT BY LEVEL <= 7
            )
            WHERE TO_CHAR(dt, 'DY', 'NLS_DATE_LANGUAGE=ENGLISH') != 'FRI'
              AND NOT EXISTS (
                    SELECT 1
                    FROM HRM.CALENDER_DETAILS c
                    WHERE dt IN (
                        TRUNC(c.HOLIDAY_1),
                        TRUNC(c.HOLIDAY_2),
                        TRUNC(c.OTHERS)
                    )
              )
        ")[0]->dt;
 
       $todayAtt = DB::select("
            SELECT
                SUM(CASE WHEN STATUS2 IN ('P','WO') THEN 1 ELSE 0 END) AS present,
                SUM(CASE WHEN STATUS2 = 'A' THEN 1 ELSE 0 END) AS absent,
                SUM(CASE WHEN STATUS2 = 'L' THEN 1 ELSE 0 END) AS on_leave,
                SUM(CASE WHEN LATE > 0 THEN 1 ELSE 0 END) AS late
            FROM HRM.ATTENDANCE_DETAILS
            WHERE TRUNC(ATT_DATE) = :dt
              AND COMPANY_ID = :cid
        ", [
            'dt' => $effectiveDate,
            'cid' => $companyId
        ])[0];
        // ── Dept Headcount ───────────────────────────────────────────────────
          $deptCount = DB::select("
            SELECT *
            FROM (
                SELECT eo.DEPT_NAME, COUNT(*) AS cnt
                FROM HRM.EMP_OFFICIAL eo
                JOIN HRM.EMP_PERSONAL ep ON ep.EMPNO = eo.EMPNO
                WHERE eo.COMPANY_ID = :cid
                  AND eo.TERMINATION_DATE IS NULL
                  AND eo.RESIGNED_DATE IS NULL
                  AND ep.STATUS = 'Active'
                GROUP BY eo.DEPT_NAME
                ORDER BY cnt DESC
            )
        ", ['cid' => $companyId]);
 
 $sectionAttToday = DB::select("
            SELECT
                NVL(e.SECTION_NAME, 'No Section')                        AS section_name,
                SUM(CASE WHEN a.STATUS2 = 'P' THEN 1 ELSE 0 END)         AS present,
                COUNT(e.EMPNO)                                            AS total,
                ROUND(
                    SUM(CASE WHEN a.STATUS2 = 'P' THEN 1 ELSE 0 END)
                    / NULLIF(COUNT(e.EMPNO), 0) * 100
                , 1) AS pct
            FROM HRM.EMP_OFFICIAL e
            JOIN HRM.EMP_PERSONAL pp ON pp.EMPNO = e.EMPNO
            LEFT JOIN HRM.ATTENDANCE_DETAILS a
                ON  a.EMPNO = e.EMPNO
                AND TRUNC(a.ATT_DATE) = :dt
            WHERE pp.STATUS = 'Active'
              AND e.COMPANY_ID = :cid
            GROUP BY NVL(e.SECTION_NAME, 'No Section')
            ORDER BY pct DESC NULLS LAST
        ", [
            'dt'  => $effectiveDate,
            'cid' => $companyId,
        ]);



        // ── Gender Split ─────────────────────────────────────────────────────
         $genderSplit = DB::select("
            SELECT ep.SEX, COUNT(*) AS cnt
            FROM HRM.EMP_PERSONAL ep
            JOIN HRM.EMP_OFFICIAL eo ON eo.EMPNO = ep.EMPNO
            WHERE eo.COMPANY_ID = :cid
              AND eo.TERMINATION_DATE IS NULL
              AND eo.RESIGNED_DATE IS NULL
              AND ep.STATUS = 'Active'
            GROUP BY ep.SEX
        ", ['cid' => $companyId]);
 
        // ── Avg Monthly OT (last 6 months) ───────────────────────────────────
          $avgOT = DB::select("
            SELECT
                TO_CHAR(ATT_DATE,'Mon YYYY') AS att_month,
                ROUND(AVG(OTHOUR + NVL(OTHOUR2,0) + NVL(OTHOUR3,0)),2) AS avg_ot
            FROM HRM.ATTENDANCE_DETAILS
            WHERE COMPANY_ID = :cid
              AND ATT_DATE >= ADD_MONTHS(TRUNC(SYSDATE,'MM'), -5)
              AND OTHOUR > 0
            GROUP BY TRUNC(ATT_DATE,'MM'), TO_CHAR(ATT_DATE,'Mon YYYY')
            ORDER BY TRUNC(ATT_DATE,'MM')
        ", ['cid' => $companyId]);
 
        // ── Alerts: Probation / Increment counts ─────────────────────────────
        $probationEnd = DB::select("
    SELECT eo.EMPNO,
           ep.FIRST_NAME || ' ' || ep.LAST_NAME AS EMP_NAME,
           eo.DEPT_NAME,
           eo.DESIGNATION_NAME,
           eo.JOINING_DATE,
           eo.CONFORM_DATE,
           eo.PROVISION_PERIOD,
           ROUND(MONTHS_BETWEEN(SYSDATE, eo.JOINING_DATE)) AS months_served
    FROM HRM.EMP_OFFICIAL eo
    JOIN HRM.EMP_PERSONAL ep ON ep.EMPNO = eo.EMPNO
    WHERE eo.COMPANY_ID = :cid
      AND eo.CONFORM_DATE IS NULL
      AND TRUNC(ADD_MONTHS(eo.JOINING_DATE,
            TO_NUMBER(NVL(eo.PROVISION_PERIOD,'3'))),'MM')
          = TRUNC(SYSDATE,'MM')
        ", ['cid' => $companyId]);
       $incrementThisMonth = DB::select("
           SELECT
  eo.EMPNO,
  ep.FIRST_NAME || ' ' || ep.LAST_NAME AS EMP_NAME,
  eo.DEPT_NAME,
  eo.DESIGNATION_NAME,
  eo.GROSS,
  eo.INCREMENT_DATE,
  NVL(
    (
      SELECT MAX(ii.CUR_GROSS)
      KEEP (DENSE_RANK FIRST ORDER BY ii.INCR_DATE DESC)
      FROM HRM.INCREMENT_INFO ii
      WHERE TRIM(ii.EMPNO) = TRIM(eo.EMPNO)
        AND ii.CUR_GROSS IS NOT NULL
    ),
    eo.GROSS
  ) AS LAST_GROSS
FROM HRM.EMP_OFFICIAL eo
JOIN HRM.EMP_PERSONAL ep ON ep.EMPNO = eo.EMPNO
WHERE eo.COMPANY_ID = :cid
  AND ep.STATUS = 'Active'
  AND eo.TERMINATION_DATE IS NULL
  AND eo.RESIGNED_DATE IS NULL
  AND eo.INCREMENT_DATE IS NOT NULL
  AND EXTRACT(MONTH FROM NVL(eo.INCREMENT_DATE, eo.JOINING_DATE)) = EXTRACT(MONTH FROM SYSDATE)
ORDER BY eo.INCREMENT_DATE
        ", ['cid' => $companyId]);
       
       
       
        $incrementNextMonth = DB::select("
    SELECT eo.EMPNO,
       ep.FIRST_NAME || ' ' || ep.LAST_NAME AS EMP_NAME,
       eo.DEPT_NAME,
       eo.DESIGNATION_NAME,
       eo.GROSS,
       eo.INCREMENT_DATE
FROM HRM.EMP_OFFICIAL eo
JOIN HRM.EMP_PERSONAL ep ON ep.EMPNO = eo.EMPNO
WHERE eo.COMPANY_ID = :cid
  AND eo.TERMINATION_DATE IS NULL
  AND eo.RESIGNED_DATE IS NULL
    AND ep.STATUS = 'Active'
  AND EXTRACT(MONTH FROM NVL(eo.INCREMENT_DATE, eo.JOINING_DATE))=EXTRACT(MONTH FROM ADD_MONTHS(SYSDATE,1))
ORDER BY eo.INCREMENT_DATE
", ['cid' => $companyId]);
 $formatted = DB::selectOne("
    SELECT TO_CHAR(SYSDATE, 'DD Mon YYYY, HH:MI:SS AM') AS formatted_time
    FROM dual
")->formatted_time;
        // ── Attendance rate % ─────────────────────────────────────────────────
        $attendanceRate = $totalEmployees > 0
            ? round((($todayAtt->present ?? 0) / $totalEmployees) * 100, 1)
            : 0;
 
        return response()->json([
            'success'            => true,
            'updated_at'         => $formatted,
            // KPI cards
            'total_employees'    => number_format($totalEmployees),
            'present'            => $todayAtt->present ?? 0,
            'absent'             => $todayAtt->absent  ?? 0,
            'on_leave'           => $todayAtt->on_leave ?? 0,
            'late'               => $todayAtt->late    ?? 0,
            'attendance_rate'    => $attendanceRate,
            // Charts
            'deptCount'         => $deptCount,
            'sectionAttToday'    => $sectionAttToday,   // ← ADD THIS LINE
            'genderSplit'       => $genderSplit,
            'avg_ot'             => $avgOT,
            // Alert badge counts
            'probationEnd'    => $probationEnd,
            'incrementThisMonth'     => $incrementThisMonth,
            'incrementNextMonth'     => $incrementNextMonth,
        ]);
 
    } catch (\Exception $e) {
        \Log::error('Dashboard liveData error: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}


}