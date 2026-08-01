<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HrmDashboardController extends BaseController
{
    public function index()
    {
        $companyId = session('company_id', '100');

        $today = Carbon::today();

        // Effective working date: skips Friday and holidays.
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

        // 1. Total employees.
        $totalEmployees = DB::select("
            SELECT COUNT(*) AS cnt
            FROM HRM.EMP_OFFICIAL eo
            JOIN HRM.EMP_PERSONAL ep ON ep.EMPNO = eo.EMPNO
            WHERE eo.COMPANY_ID = :cid
              AND eo.TERMINATION_DATE IS NULL
              AND eo.RESIGNED_DATE IS NULL
              AND ep.STATUS = 'Active'
        ", ['cid' => $companyId])[0]->cnt ?? 0;

        // 2. Attendance for the effective working day.
        $todayAtt = DB::select("
            SELECT
                SUM(CASE WHEN STATUS2 IN ('P','WO') THEN 1 ELSE 0 END) AS present,
                SUM(CASE WHEN STATUS2 = 'A' THEN 1 ELSE 0 END)          AS absent,
                SUM(CASE WHEN STATUS2 = 'L' THEN 1 ELSE 0 END)          AS on_leave,
                SUM(CASE WHEN LATE > 0 THEN 1 ELSE 0 END)               AS late
            FROM HRM.ATTENDANCE_DETAILS
            WHERE TRUNC(ATT_DATE) = :dt
              AND COMPANY_ID = :cid
        ", [
            'dt'  => $effectiveDate,
            'cid' => $companyId,
        ])[0];

        // 3. Gender split.
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

        // 4. Department headcount.
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

        // 5. Average overtime (last 6 months).
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

        // 6. Leave summary (current year).
        $leaveSummary = DB::select("
            SELECT li.LEAVE_NAME,
                   SUM(led.BALANCE)       AS total_balance,
                   SUM(led.APPROVE_DAYS)  AS total_taken
            FROM HRM.LEAVE_ENTRY_DETAILS led
            JOIN HRM.LEAVE_INFO li    ON li.LEAVE_ID  = led.LEAVE_ID
            JOIN HRM.EMP_OFFICIAL eo  ON eo.EMPNO     = led.EMPNO
            WHERE led.YEAR = TO_CHAR(SYSDATE,'YYYY')
              AND eo.COMPANY_ID = :cid
              AND eo.TERMINATION_DATE IS NULL
            GROUP BY li.LEAVE_NAME
            ORDER BY total_taken DESC
        ", ['cid' => $companyId]);

        // 7. Probation ending this month.
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

        // 8. Increment due this month.
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

        // 9. Recent joiners (last 30 days, top 8).
        $recentJoiners = DB::select("
            SELECT *
            FROM (
                SELECT eo.EMPNO,
                       ep.FIRST_NAME || ' ' || ep.LAST_NAME AS EMP_NAME,
                       eo.DEPT_NAME,
                       eo.DESIGNATION_NAME,
                       eo.JOINING_DATE,
                       ep.SEX
                FROM HRM.EMP_OFFICIAL eo
                JOIN HRM.EMP_PERSONAL ep ON ep.EMPNO = eo.EMPNO
                WHERE eo.COMPANY_ID = :cid
                  AND eo.JOINING_DATE >= SYSDATE - 30
                ORDER BY eo.JOINING_DATE DESC
            )
            WHERE ROWNUM <= 8
        ", ['cid' => $companyId]);

        // 10. Increment due next month.
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
              AND EXTRACT(MONTH FROM NVL(eo.INCREMENT_DATE, eo.JOINING_DATE))
                  = EXTRACT(MONTH FROM ADD_MONTHS(SYSDATE, 1))
            ORDER BY eo.INCREMENT_DATE
        ", ['cid' => $companyId]);

        // 11. Attendance heatmap: daily present % for current month.
        // FIX: added HRM. schema prefix (was missing).
        $attendanceHeatmap = DB::select("
            SELECT
                TO_NUMBER(TO_CHAR(ATT_DATE, 'DD')) AS day,
                ROUND(
                    SUM(CASE WHEN STATUS2 = 'P' THEN 1 ELSE 0 END)
                    / NULLIF(COUNT(*), 0) * 100
                , 1) AS pct
            FROM HRM.ATTENDANCE_DETAILS
            WHERE ATT_DATE >= TRUNC(SYSDATE, 'MM')
              AND ATT_DATE <  LAST_DAY(SYSDATE) + 1
              AND COMPANY_ID = :cid
            GROUP BY TO_NUMBER(TO_CHAR(ATT_DATE, 'DD'))
            ORDER BY day
        ", ['cid' => $companyId]);

        // 12. Section attendance rate today, sorted best to worst.
        // FIX: added HRM. schema prefix.
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

        // 13. Late arrival trend: last 7 days.
        // FIX: added HRM. schema prefix and COMPANY_ID filter.
        $lateTrend = DB::select("
            SELECT
                ATT_DATE,
                SUM(CASE WHEN LATE > 0 THEN 1 ELSE 0 END) AS late_count
            FROM HRM.ATTENDANCE_DETAILS
            WHERE ATT_DATE >= TRUNC(SYSDATE) - 6
              AND ATT_DATE <= TRUNC(SYSDATE)
              AND COMPANY_ID = :cid
            GROUP BY ATT_DATE
            ORDER BY ATT_DATE
        ", ['cid' => $companyId]);

        // 14. OT cost estimate by department for the current month.
        // FIX: added HRM. schema prefix; removed invalid HRM.DEPT join
        //      (dept name comes directly from EMP_OFFICIAL, consistent with rest of codebase).
        $otCostByDept = DB::select("
            SELECT
                e.DEPT_NAME,
                ROUND(SUM(o.OTHOUR), 2)                       AS total_ot_hrs,
                ROUND(AVG(e.GROSS / 208), 2)                  AS avg_hourly_rate,
                ROUND(SUM(o.OTHOUR) * AVG(e.GROSS / 208), 2) AS estimated_cost
            FROM HRM.ATTENDANCE_DETAILS o
            JOIN HRM.EMP_OFFICIAL e  ON e.EMPNO = o.EMPNO
            JOIN HRM.EMP_PERSONAL pp ON e.EMPNO = pp.EMPNO
            WHERE o.ATT_DATE >= TRUNC(SYSDATE, 'MM')
              AND o.ATT_DATE <  LAST_DAY(SYSDATE) + 1
              AND pp.STATUS = 'Active'
              AND e.COMPANY_ID = :cid
              AND o.OTHOUR > 0
            GROUP BY e.DEPT_NAME
            ORDER BY estimated_cost DESC
        ", ['cid' => $companyId]);

        // FIX: all variables now included in compact() — probationEnd,
        //      incrementThisMonth, incrementNextMonth were previously missing.
        return view('dashboard', compact(
            'totalEmployees',
            'todayAtt',
            'genderSplit',
            'deptCount',
            'avgOT',
            'leaveSummary',
            'probationEnd',
            'incrementThisMonth',
            'incrementNextMonth',
            'recentJoiners',
            'today',
            'effectiveDate',
            'attendanceHeatmap',
            'sectionAttToday',
            'lateTrend',
            'otCostByDept'
        ));
    }
}