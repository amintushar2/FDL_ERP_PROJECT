<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class IncrementEntryController extends Controller
{
    // ─── View ─────────────────────────────────────────────────────────────────

    public function index()
    {
        return view('payroll.increment_entry.index');
    }

    // ─── LOV: Employee search (EMP_INCREMENT_VW) ─────────────────────────────

    public function getEmployees(Request $request)
    {
        $search = trim($request->q ?? '');

        $rows = DB::select("
            SELECT EP.EMPNO,
                   EP.NEW_EMPNO,
                   EP.FIRST_NAME || ' ' || EP.MIDDLE_NAME || ' ' || EP.LAST_NAME AS EMP_NAME,
                   EO.SECTION_NAME,
                   EO.DES_NAME
            FROM EMP_PERSONAL EP
            JOIN EMP_OFFICIAL EO ON EO.EMPNO = EP.EMPNO
            WHERE EP.STATUS = 'Active'
              AND (
                    UPPER(EP.EMPNO)     LIKE UPPER(:s1)
                 OR UPPER(EP.NEW_EMPNO) LIKE UPPER(:s2)
                 OR UPPER(EP.FIRST_NAME || ' ' || EP.MIDDLE_NAME || ' ' || EP.LAST_NAME) LIKE UPPER(:s3)
              )
            ORDER BY EP.EMPNO
        ", [
            's1' => '%'.$search.'%',
            's2' => '%'.$search.'%',
            's3' => '%'.$search.'%',
        ]);

        return response()->json(array_map(
            fn($r) => array_change_key_case((array)$r, CASE_LOWER),
            $rows
        ));
    }

    // ─── Load employee info when selected ─────────────────────────────────────

    public function getEmployeeInfo(Request $request)
    {
        $empno = trim($request->empno ?? '');
        if (!$empno) return response()->json(null);

        // Employee basic info
        $emp = DB::selectOne("
            SELECT EP.FIRST_NAME || ' ' || EP.MIDDLE_NAME || ' ' || EP.LAST_NAME AS EMP_NAME,
                   EO.SECTION_NAME  AS SECTION,
                   EO.DES_NAME      AS PREV_DESIGNATION,
                   EO.DES_ID        AS PREV_DES_ID,
                   EO.OT_ENT        AS PREV_OT_ENT,
                   EO.GROSS         AS PREV_GROSS,
                   EO.GRADE_ID      AS PRE_GRADE_ID,
                   EO.GRADE_NAME    AS PREV_GRADE,
                   EP.NEW_EMPNO
            FROM EMP_PERSONAL EP
            JOIN EMP_OFFICIAL EO ON EO.EMPNO = EP.EMPNO
            WHERE EP.STATUS  = 'Active'
              AND EP.EMPNO   = :empno
        ", ['empno' => $empno]);

        if (!$emp) return response()->json(['error' => 'Employee not found.'], 404);

        // Previous payment breakdown
        $pay = DB::selectOne("
            SELECT BASIC, HR_AMT, MR_AMT
            FROM HRM.EMP_PAYMENT
            WHERE NEW_EMPNO = :new_empno
        ", ['new_empno' => $emp->new_empno ?? $empno]);

        $result = array_change_key_case((array)$emp, CASE_LOWER);
        $result['prev_basic']      = $pay->basic  ?? null;
        $result['prev_house_rent'] = $pay->hr_amt  ?? null;
        $result['prev_medical']    = $pay->mr_amt  ?? null;

        return response()->json($result);
    }

    // ─── Load increment history for selected employee ─────────────────────────

    public function getHistory(Request $request)
    {
        $empno = trim($request->empno ?? '');
        if (!$empno) return response()->json([]);

        $rows = DB::select("
            SELECT EMPNO, NEW_EMPNO, EMP_NAME, SECTION,
                   PREV_DESIGNATION, CUR_DESIGNATION,
                   PREV_DES_ID, CUR_DES_ID,
                   PREV_OT_ENT, CUR_OT_ENT,
                   PREV_GROSS, INCREMENT_AMT, CUR_GROSS,
                   INCR_TYPE,
                   TO_CHAR(INCR_DATE,'DD-MON-YYYY')      AS INCR_DATE,
                   TO_CHAR(EFFECTIVE_DATE,'DD-MON-YYYY')  AS EFFECTIVE_DATE,
                   REMARK_TEXT,
                   PREV_BASIC, PREV_HOUSE_RENT, PREV_MEDICAL,
                   CUR_BASIC,  CUR_HOUSE_RENT,  CUR_MEDICAL,
                   PREV_GRADE, CUR_GREDE AS CUR_GRADE,
                   PREV_GRADE_NAME, CUR_GRADE_NAME
            FROM INCREMENT_INFO
            WHERE EMPNO = :empno
            ORDER BY TO_DATE(INCR_DATE, 'DD-MON-YYYY')    DESC
        ", ['empno' => $empno]);

        return response()->json(array_map(
            fn($r) => array_change_key_case((array)$r, CASE_LOWER),
            $rows
        ));
    }

    // ─── Calculate new salary breakdown when INCR_TYPE / INCREMENT_AMT changes ──

    public function calculate(Request $request)
    {
        $prevGross   = (float)($request->prev_gross    ?? 0);
        $incrType    = trim($request->incr_type        ?? '');
        $incrementAmt= (float)($request->increment_amt ?? 0);

        if (!$prevGross || !$incrType || !$incrementAmt) {
            return response()->json(['error' => 'prev_gross, incr_type and increment_amt required.'], 422);
        }

        // Compute new GROSS
        $curGross = match($incrType) {
            'Fixed'   => round($prevGross + $incrementAmt),
            'Percent' => round($prevGross + ($prevGross * $incrementAmt / 100)),
            default   => $prevGross,
        };

        // Fetch SALARY_PARAMETER for breakdown
        $sp = DB::selectOne("SELECT BASIC_AMT, BASIC_PER, HR_AMT, HR_PER, MR_AMT, MR_PER,
                                    NVL(MR_AMT,0)+NVL(CONVANCE_AMT,0)+NVL(FOOD_AMT,0) AS OTHER_AMT
                             FROM SALARY_PARAMETER
                             WHERE ROWNUM = 1");

        $vBasic = $curGross;
        $vOthers= $sp ? (float)$sp->other_amt : 0;

        // MEDICAL
        if ($sp && $sp->mr_amt > 0) {
            $curMedical = (float)$sp->mr_amt;
        } elseif ($sp && $sp->mr_per > 0) {
            $curMedical = round($vBasic * $sp->mr_per / 100);
        } else {
            $curMedical = 0;
        }

        // BASIC
        if ($sp && $sp->basic_amt > 0) {
            $curBasic = (float)$sp->basic_amt;
        } elseif ($sp && $sp->basic_per > 0) {
            $curBasic = round($vBasic * $sp->basic_per / 100);
        } else {
            $curBasic = round(($vBasic - $vOthers) / 1.5);
        }

        // HOUSE RENT
        $vBasicForHR = $curBasic;
        if ($sp && $sp->hr_amt > 0) {
            $curHouseRent = (float)$sp->hr_amt;
        } elseif ($sp && $sp->hr_per > 0) {
            $curHouseRent = round($vBasicForHR * $sp->hr_per / 100);
        } else {
            $curHouseRent = round((($vBasicForHR - $curMedical) / 1.3) * 30 / 100);
        }

        return response()->json([
            'cur_gross'      => $curGross,
            'cur_basic'      => $curBasic,
            'cur_house_rent' => $curHouseRent,
            'cur_medical'    => $curMedical,
        ]);
    }

    // ─── LOV: Designation list ─────────────────────────────────────────────────

    public function getDesignations(Request $request)
    {
        $search = trim($request->q ?? '');

        $rows = DB::select("
            SELECT DES_ID, DESIGNATION_NAME
            FROM DESIGNATION_DETAILS
            WHERE UPPER(DESIGNATION_NAME) LIKE UPPER(:s)
            ORDER BY DESIGNATION_NAME
        ", ['s' => '%'.$search.'%']);

        return response()->json(array_map(
            fn($r) => array_change_key_case((array)$r, CASE_LOWER),
            $rows
        ));
    }

    // ─── LOV: Grade list ───────────────────────────────────────────────────────

    public function getGrades()
    {
        $rows = DB::select("
            SELECT NULL AS GRADE_ID, NULL AS GRADE_NAME FROM DUAL
            UNION ALL
            SELECT GRADE_ID, GRADE_NAME FROM GRADE
            ORDER BY GRADE_NAME
        ");

        return response()->json(array_map(
            fn($r) => array_change_key_case((array)$r, CASE_LOWER),
            $rows
        ));
    }

    // ─── Save (INSERT) increment record ───────────────────────────────────────

    public function save(Request $request)
    {
        $request->validate([
            'empno'         => 'required',
            'incr_type'     => 'required',
            'increment_amt' => 'required|numeric',
            'incr_date'     => 'required',
        ]);

        $incrDate     = $this->normaliseDate($request->incr_date);
        $effectiveDate= $this->normaliseDate($request->effective_date ?? '');

        if (!$incrDate) {
            return response()->json(['success' => false, 'message' => 'Invalid Increment Date.'], 422);
        }

        $isEdit       = (bool)($request->is_edit ?? false);
        $origIncrDate = $this->normaliseDate($request->orig_incr_date ?? '') ?: $incrDate;

        DB::transaction(function () use ($request, $incrDate, $effectiveDate, $isEdit, $origIncrDate) {

            if ($isEdit) {
                // ── UPDATE existing INCREMENT_INFO record ─────────────────────
                DB::statement("
                    UPDATE INCREMENT_INFO SET
                        NEW_EMPNO        = :p_new_empno,
                        EMP_NAME         = :p_emp_name,
                        SECTION          = :p_section,
                        PREV_DESIGNATION = :p_prev_designation,
                        PREV_DES_ID      = :p_prev_des_id,
                        CUR_DESIGNATION  = :p_cur_designation,
                        CUR_DES_ID       = :p_cur_des_id,
                        PREV_OT_ENT      = :p_prev_ot_ent,
                        CUR_OT_ENT       = :p_cur_ot_ent,
                        PREV_GROSS       = :p_prev_gross,
                        INCREMENT_AMT    = :p_increment_amt,
                        CUR_GROSS        = :p_cur_gross,
                        INCR_TYPE        = :p_incr_type,
                        INCR_DATE        = TO_DATE(:p_incr_date,     'DD-MON-YYYY'),
                        EFFECTIVE_DATE   = TO_DATE(:p_effective_date,'DD-MON-YYYY'),
                        REMARK_TEXT      = :p_remark_text,
                        PREV_BASIC       = :p_prev_basic,
                        PREV_HOUSE_RENT  = :p_prev_house_rent,
                        PREV_MEDICAL     = :p_prev_medical,
                        CUR_BASIC        = :p_cur_basic,
                        CUR_HOUSE_RENT   = :p_cur_house_rent,
                        CUR_MEDICAL      = :p_cur_medical,
                        PREV_GRADE       = :p_prev_grade,
                        PREV_GRADE_NAME  = :p_prev_grade_name,
                        CUR_GREDE        = :p_cur_grade,
                        CUR_GRADE_NAME   = :p_cur_grade_name
                    WHERE EMPNO     = :p_empno
                      AND INCR_DATE = TO_DATE(:p_orig_incr_date,'DD-MON-YYYY')
                ", [
                    'p_new_empno'        => $request->new_empno        ?: null,
                    'p_emp_name'         => $request->emp_name         ?: null,
                    'p_section'          => $request->section          ?: null,
                    'p_prev_designation' => $request->prev_designation ?: null,
                    'p_prev_des_id'      => $request->prev_des_id      ?: null,
                    'p_cur_designation'  => $request->cur_designation  ?: null,
                    'p_cur_des_id'       => $request->cur_des_id       ?: null,
                    'p_prev_ot_ent'      => $request->prev_ot_ent      ?: null,
                    'p_cur_ot_ent'       => $request->cur_ot_ent       ?: null,
                    'p_prev_gross'       => $request->prev_gross       ?: null,
                    'p_increment_amt'    => $request->increment_amt,
                    'p_cur_gross'        => $request->cur_gross        ?: null,
                    'p_incr_type'        => $request->incr_type,
                    'p_incr_date'        => $incrDate,
                    'p_effective_date'   => $effectiveDate ?: $incrDate,
                    'p_remark_text'      => $request->remark_text      ?: null,
                    'p_prev_basic'       => $request->prev_basic       ?: null,
                    'p_prev_house_rent'  => $request->prev_house_rent  ?: null,
                    'p_prev_medical'     => $request->prev_medical     ?: null,
                    'p_cur_basic'        => $request->cur_basic        ?: null,
                    'p_cur_house_rent'   => $request->cur_house_rent   ?: null,
                    'p_cur_medical'      => $request->cur_medical      ?: null,
                    'p_prev_grade'       => $request->prev_grade       ?: null,
                    'p_prev_grade_name'  => $request->prev_grade_name  ?: null,
                    'p_cur_grade'        => $request->cur_grade        ?: null,
                    'p_cur_grade_name'   => $request->cur_grade_name   ?: null,
                    'p_empno'            => $request->empno,
                    'p_orig_incr_date'   => $origIncrDate,
                ]);

            } else {
            // ── INSERT new INCREMENT_INFO record ──────────────────────────────
            DB::statement("
                INSERT INTO INCREMENT_INFO (
                    EMPNO, NEW_EMPNO, EMP_NAME, SECTION,
                    PREV_DESIGNATION, PREV_DES_ID, CUR_DESIGNATION, CUR_DES_ID,
                    PREV_OT_ENT, CUR_OT_ENT,
                    PREV_GROSS, INCREMENT_AMT, CUR_GROSS,
                    INCR_TYPE, INCR_DATE, EFFECTIVE_DATE, REMARK_TEXT,
                    PREV_BASIC, PREV_HOUSE_RENT, PREV_MEDICAL,
                    CUR_BASIC,  CUR_HOUSE_RENT,  CUR_MEDICAL,
                    PREV_GRADE, PREV_GRADE_NAME,
                    CUR_GREDE,  CUR_GRADE_NAME
                ) VALUES (
                    :p_empno,            :p_new_empno,    :p_emp_name,       :p_section,
                    :p_prev_designation, :p_prev_des_id,  :p_cur_designation,:p_cur_des_id,
                    :p_prev_ot_ent,      :p_cur_ot_ent,
                    :p_prev_gross,       :p_increment_amt,:p_cur_gross,
                    :p_incr_type,
                    TO_DATE(:p_incr_date,     'DD-MON-YYYY'),
                    TO_DATE(:p_effective_date, 'DD-MON-YYYY'),
                    :p_remark_text,
                    :p_prev_basic, :p_prev_house_rent, :p_prev_medical,
                    :p_cur_basic,  :p_cur_house_rent,  :p_cur_medical,
                    :p_prev_grade, :p_prev_grade_name,
                    :p_cur_grade,  :p_cur_grade_name
                )
            ", [
                'p_empno'            => $request->empno,
                'p_new_empno'        => $request->new_empno        ?: null,
                'p_emp_name'         => $request->emp_name         ?: null,
                'p_section'          => $request->section          ?: null,
                'p_prev_designation' => $request->prev_designation ?: null,
                'p_prev_des_id'      => $request->prev_des_id      ?: null,
                'p_cur_designation'  => $request->cur_designation  ?: null,
                'p_cur_des_id'       => $request->cur_des_id       ?: null,
                'p_prev_ot_ent'      => $request->prev_ot_ent      ?: null,
                'p_cur_ot_ent'       => $request->cur_ot_ent       ?: null,
                'p_prev_gross'       => $request->prev_gross       ?: null,
                'p_increment_amt'    => $request->increment_amt,
                'p_cur_gross'        => $request->cur_gross        ?: null,
                'p_incr_type'        => $request->incr_type,
                'p_incr_date'        => $incrDate,
                'p_effective_date'   => $effectiveDate ?: $incrDate,
                'p_remark_text'      => $request->remark_text      ?: null,
                'p_prev_basic'       => $request->prev_basic       ?: null,
                'p_prev_house_rent'  => $request->prev_house_rent  ?: null,
                'p_prev_medical'     => $request->prev_medical     ?: null,
                'p_cur_basic'        => $request->cur_basic        ?: null,
                'p_cur_house_rent'   => $request->cur_house_rent   ?: null,
                'p_cur_medical'      => $request->cur_medical      ?: null,
                'p_prev_grade'       => $request->prev_grade       ?: null,
                'p_prev_grade_name'  => $request->prev_grade_name  ?: null,
                'p_cur_grade'        => $request->cur_grade        ?: null,
                'p_cur_grade_name'   => $request->cur_grade_name   ?: null,
            ]);
            } // end if/else insert

            // UPDATE EMP_OFFICIAL with new salary info (both insert and update)
            // Also UPDATE EMP_PAYMENT with new payroll breakdown
            DB::statement("
                UPDATE EMP_OFFICIAL SET
                    GROSS      = :p_gross,
                    DES_ID     = :p_des_id,
                    DES_NAME   = :p_des_name,
                    OT_ENT     = :p_ot_ent,
                    GRADE_ID   = :p_grade_id,
                    GRADE_NAME = :p_grade_name
                WHERE EMPNO    = :p_empno
            ", [
                'p_gross'      => $request->cur_gross       ?: null,
                'p_des_id'     => $request->cur_des_id      ?: null,
                'p_des_name'   => $request->cur_designation ?: null,
                'p_ot_ent'     => $request->cur_ot_ent      ?: null,
                'p_grade_id'   => $request->cur_grade       ?: null,
                'p_grade_name' => $request->cur_grade_name  ?: null,
                'p_empno'      => $request->empno,
            ]);

            // UPDATE EMP_PAYMENT payroll breakdown (BASIC, HR_AMT, MR_AMT)
            $payCount = DB::selectOne(
                "SELECT COUNT(1) AS CNT FROM HRM.EMP_PAYMENT WHERE NEW_EMPNO = :new_empno",
                ['new_empno' => $request->new_empno ?: $request->empno]
            );

            if ($payCount && $payCount->cnt > 0) {
                DB::statement("
                    UPDATE HRM.EMP_PAYMENT SET
                        BASIC   = :p_basic,
                        HR_AMT  = :p_hr_amt,
                        MR_AMT  = :p_mr_amt
                    WHERE NEW_EMPNO = :p_new_empno
                ", [
                    'p_basic'    => $request->cur_basic       ?: null,
                    'p_hr_amt'   => $request->cur_house_rent  ?: null,
                    'p_mr_amt'   => $request->cur_medical     ?: null,
                    'p_new_empno'=> $request->new_empno ?: $request->empno,
                ]);
            }
        });

        return response()->json(['success' => true, 'message' => $isEdit ? 'Increment updated successfully.' : 'Increment saved successfully.']);
    }

    // ─── Delete increment record ───────────────────────────────────────────────

    public function delete(Request $request)
    {
        $request->validate([
            'empno'     => 'required',
            'incr_date' => 'required',
        ]);

        $incrDate = $this->normaliseDate($request->incr_date);

        DB::transaction(function () use ($request, $incrDate) {

            // Restore previous values to EMP_OFFICIAL (ON-DELETE: revert to prev salary)
            DB::statement("
                UPDATE EMP_OFFICIAL SET
                    GROSS      = :p_gross,
                    DES_NAME   = :p_des_name,
                    DES_ID     = :p_des_id,
                    OT_ENT     = :p_ot_ent,
                    GRADE_NAME = :p_grade_name
                WHERE EMPNO    = :p_empno
            ", [
                'p_gross'      => $request->prev_gross       ?: null,
                'p_des_name'   => $request->prev_designation ?: null,
                'p_des_id'     => $request->prev_des_id      ?: null,
                'p_ot_ent'     => $request->prev_ot_ent      ?: null,
                'p_grade_name' => $request->prev_grade_name  ?: null,
                'p_empno'      => $request->empno,
            ]);

            // ON-DELETE: also delete EMP_PAYMENT row (original form behaviour)
            DB::statement(
                "DELETE FROM EMP_PAYMENT WHERE EMPNO = :p_empno",
                ['p_empno' => $request->empno]
            );

            // Delete the INCREMENT_INFO record
            DB::statement("
                DELETE FROM INCREMENT_INFO
                WHERE EMPNO     = :p_empno
                  AND INCR_DATE = TO_DATE(:p_incr_date,'DD-MON-YYYY')
            ", ['p_empno' => $request->empno, 'p_incr_date' => $incrDate]);
        });

        return response()->json(['success' => true, 'message' => 'Increment record deleted.']);
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    private function normaliseDate(?string $raw): ?string
    {
        $raw = strtoupper(trim($raw ?? ''));
        if ($raw === '') return null;
        $months = ['01'=>'JAN','02'=>'FEB','03'=>'MAR','04'=>'APR',
                   '05'=>'MAY','06'=>'JUN','07'=>'JUL','08'=>'AUG',
                   '09'=>'SEP','10'=>'OCT','11'=>'NOV','12'=>'DEC'];
        if (preg_match('/^\d{2}-[A-Z]{3}-\d{4}$/', $raw)) return $raw;
        if (preg_match('/^(\d{1,2})[-\/\.](\d{1,2})[-\/\.](\d{2,4})$/', $raw, $m)) {
            $d  = sprintf('%02d', (int)$m[1]);
            $mo = sprintf('%02d', (int)$m[2]);
            $y  = strlen($m[3]) === 2 ? ((int)$m[3] <= 50 ? 2000+(int)$m[3] : 1900+(int)$m[3]) : (int)$m[3];
            return isset($months[$mo]) ? "{$d}-{$months[$mo]}-{$y}" : null;
        }
        $d = preg_replace('/\D/','',$raw);
        if (strlen($d) === 8) {
            $dd=$d[0].$d[1]; $mo=$d[2].$d[3]; $y=substr($d,4);
            return isset($months[$mo]) ? "{$dd}-{$months[$mo]}-{$y}" : null;
        }
        return \Carbon\Carbon::parse($raw)->format('d-M-Y') ?: null;
    }
}
