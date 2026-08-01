<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll\EmpLoanDetail;
use App\Models\Payroll\EmpLoanMaster;
use App\Models\Payroll\EmpLoanSetting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdvanceLoanController extends Controller
{
    /* ══════════════════════════════════════════════════════════════════
       INDEX  –  Blade view
    ══════════════════════════════════════════════════════════════════ */
    public function index(Request $request)
    {
        $companies = DB::table('company_profile as cp')
            ->join('company_permission as cpm', 'cp.company_id', '=', 'cpm.company_id')
            ->select('cp.company_id', 'cp.company_name')
            ->orderBy('cp.company_name')
            ->distinct()
            ->get();

        return view('payroll.advance-loan.entry', compact('companies'));
    }

    /* ══════════════════════════════════════════════════════════════════
       SHOW  –  load one record with details
    ══════════════════════════════════════════════════════════════════ */
    public function show(Request $request): JsonResponse
    {
        // ── LOV browse: list all loans with DB function name lookups ──
        if ($request->filled('list')) {
            $loans = EmpLoanMaster::where('company_id', $request->company_id)
                ->orderByDesc('loan_app_no')
                ->limit(500)
                ->get()
                ->map(fn($l) => [
                    'loan_app_no'     => $l->loan_app_no,
                    'emp_no'          => $l->emp_no,
                    'employe_name'    => $l->employe_name
                                        ?: (DB::selectOne('SELECT HRM.GET_EMP_NAME(?) AS n from DUAL', [$l->emp_no])?->n ?? ''),
                    'des_name'        => $l->des_name ?? '',
                    'dept_name'       => $l->dept_name
                                        ?: (DB::selectOne('SELECT get_dept_name(?) AS n from DUAL', [$l->dept_no])?->n ?? ''),
                    'sec_name'        => $l->sec_name
                                        ?: (DB::selectOne('SELECT get_section(?) AS n from DUAL', [$l->section_no])?->n ?? ''),
                    'sanction_amount' => $l->sanction_amount,
                    'period'          => $l->period,
                    'application_date'=> $l->application_date?->format('d-M-Y'),
                    'is_close'        => $l->is_close,
                ]);
            return response()->json(['data' => $loans]);
        }

        // ── Single record load ──
        $query = EmpLoanMaster::with(['details'])
                    ->where('company_id', $request->company_id);

        if ($request->filled('loan_app_no')) {
            $query->where('loan_app_no', $request->loan_app_no);
        } elseif ($request->filled('emp_no')) {
            $query->where('emp_no', $request->emp_no)->latest('application_date');
        } else {
            return response()->json(['error' => 'Provide loan_app_no or emp_no'], 422);
        }

        $loan = $query->first();
        if (!$loan) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        // Enrich with DB function values if stored fields are empty
        $data = $this->formatLoan($loan);
        $data['employe_name'] = $loan->employe_name
                               ?: (DB::selectOne('SELECT HRM.GET_EMP_NAME(?) AS n from DUAL', [$loan->emp_no])?->n ?? '');
        $data['dept_name']    = $loan->dept_name
                               ?: (DB::selectOne('SELECT get_dept_name(?) AS n from DUAL', [$loan->dept_no])?->n ?? '');
        $data['sec_name']     = $loan->sec_name
                               ?: (DB::selectOne('SELECT get_section(?) AS n from DUAL', [$loan->section_no])?->n ?? '');

        return response()->json($data);
    }

    /* ══════════════════════════════════════════════════════════════════
       STORE
    ══════════════════════════════════════════════════════════════════ */
    public function store(Request $request): JsonResponse
    {
        $data = $this->validateMaster($request);

        DB::beginTransaction();
        try {
            $data['loan_app_no'] = EmpLoanMaster::generateLoanAppNo($data['company_id']);

            $loan = EmpLoanMaster::create($data);
            DB::commit();

            return response()->json([
                'message'     => 'Loan saved successfully.',
                'loan_app_no' => $loan->loan_app_no,
                'details'     => [],
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /* ══════════════════════════════════════════════════════════════════
       UPDATE
    ══════════════════════════════════════════════════════════════════ */
    public function update(Request $request, string $loanAppNo): JsonResponse
    {
        $loan = EmpLoanMaster::findOrFail($loanAppNo);
        $data = $this->validateMaster($request);

        DB::beginTransaction();
        try {
            $loan->update($data);
            DB::commit();

            return response()->json([
                'message'     => 'Loan updated successfully.',
                'loan_app_no' => $loanAppNo,
                'details'     => $loan->details->map(fn($d) => $this->formatDetail($d)),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /* ══════════════════════════════════════════════════════════════════
       DESTROY
    ══════════════════════════════════════════════════════════════════ */
    public function destroy(string $loanAppNo): JsonResponse
    {
        $loan = EmpLoanMaster::findOrFail($loanAppNo);

        DB::beginTransaction();
        try {
            EmpLoanDetail::where('loan_app_no', $loanAppNo)->delete();
            $loan->delete();
            DB::commit();

            return response()->json(['message' => 'Loan record deleted successfully.']);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /* ══════════════════════════════════════════════════════════════════
       GENERATE SCHEDULE
       - End-of-month aware: 31-Jan → 28-Feb → 31-Mar → 30-Apr
       - Keeps existing Paid rows
    ══════════════════════════════════════════════════════════════════ */
    public function generateSchedule(Request $request): JsonResponse
    {
        $request->validate([
            'loan_app_no'         => 'required|exists:emp_loan_master,loan_app_no',
            'sanction_amount'     => 'required|numeric|min:0.01',
            'period'              => 'required|integer|min:1',
            'monthly_installment' => 'required|numeric|min:0.01',
            'first_install_date'  => 'required|date',
            'company_id'          => 'required|string',
        ]);

        $loanAppNo  = $request->loan_app_no;
        $pbbom      = (float) $request->sanction_amount;
        $installAmt = (float) $request->monthly_installment;
        $period     = (int)   $request->period;
        $companyId  = $request->company_id;
        $firstDate  = Carbon::parse($request->first_install_date);

        // Detect end-of-month: 31-Jan, 28-Feb, 30-Apr etc.
        $isEndOfMonth = ($firstDate->day === (int) $firstDate->copy()->endOfMonth()->day);

        DB::beginTransaction();
        try {
            // Delete only Due rows (keep Paid)
            EmpLoanDetail::where('loan_app_no', $loanAppNo)
                          ->where('status', 'Due')
                          ->delete();

            $rows = [];
            for ($i = 1; $i <= $period; $i++) {
                if ($isEndOfMonth) {
                    $installDate = $firstDate->copy()
                        ->addMonthsNoOverflow($i - 1)
                        ->endOfMonth()
                        ->startOfDay();
                } else {
                    $installDate = $firstDate->copy()->addMonthsNoOverflow($i - 1);
                }

                $pbeom  = round($pbbom - $installAmt, 2);
                $rows[] = [
                    'loan_app_no'    => $loanAppNo,
                    'company_id'     => $companyId,
                    'install_no'     => $i,
                    'install_amount' => $installAmt,
                    'install_date'   => $installDate->format('Y-m-d'),
                    'pbbom'          => round($pbbom, 2),
                    'pbeom'          => $pbeom,
                    'status'         => 'Due',
                    'is_voucher'     => 'N',
                ];
                $pbbom = $pbeom;
            }

            EmpLoanDetail::insert($rows);
            DB::commit();

            $details = EmpLoanDetail::where('loan_app_no', $loanAppNo)
                                     ->orderBy('install_no')->get()
                                     ->map(fn($d) => $this->formatDetail($d));

            return response()->json([
                'message' => 'Schedule generated successfully.',
                'details' => $details,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /* ══════════════════════════════════════════════════════════════════
       OUT-PAYMENT AMOUNT  (GET – called on from/to change)
       Matches Oracle: SELECT SUM(INSTALL_AMOUNT) FROM EMP_LOAN_DETAIL
                       WHERE LOAN_APP_NO=:b1 AND STATUS='Due'
                       AND INSTALL_NO BETWEEN :from AND :to
    ══════════════════════════════════════════════════════════════════ */
    public function outAmount(Request $request): JsonResponse
    {
        $request->validate([
            'loan_app_no' => 'required',
            'from'        => 'required|integer|min:1',
            'to'          => 'required|integer|min:1',
            'company_id'  => 'required',
        ]);

        $amount = EmpLoanDetail::where('loan_app_no', $request->loan_app_no)
            ->where('company_id', $request->company_id)
            ->where('status',     'Due')
            ->whereBetween('install_no', [$request->from, $request->to])
            ->sum('install_amount');

        return response()->json(['amount' => round((float) $amount, 2)]);
    }

    /* ══════════════════════════════════════════════════════════════════
       PROCESS OUT PAYMENT
       Matches Oracle EMP_LOAN_MASTER columns exactly:
         OUT_PAY_DATE, OUT_PAY_VOUCHER, IS_CLOSE
       Matches Oracle EMP_LOAN_DETAIL columns exactly:
         STATUS='Paid', PAYDATE, IS_VOUCHER='Y'
    ══════════════════════════════════════════════════════════════════ */
    public function processOutPayment(Request $request): JsonResponse
    {
        $request->validate([
            'loan_app_no'     => 'required|exists:emp_loan_master,loan_app_no',
            'out_from'        => 'required|integer|min:1',
            'out_to'          => 'required|integer|min:1',
            'out_pay_date'    => 'required|date',
            'out_pay_voucher' => 'nullable|string|max:50',
            'company_id'      => 'required',
        ]);

        $loanAppNo = $request->loan_app_no;

        // Verify Due rows exist in range
        $count = EmpLoanDetail::where('loan_app_no', $loanAppNo)
            ->where('company_id', $request->company_id)
            ->where('status', 'Due')
            ->whereBetween('install_no', [$request->out_from, $request->out_to])
            ->count();

        if ($count === 0) {
            return response()->json([
                'message' => 'No Due installments found in range ' . $request->out_from . ' to ' . $request->out_to . '.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Mark installments as Paid
            EmpLoanDetail::where('loan_app_no', $loanAppNo)
                ->where('company_id', $request->company_id)
                ->whereBetween('install_no', [$request->out_from, $request->out_to])
                ->where('status', 'Due')
                ->update([
                    'status'     => 'Paid',
                    'paydate'    => Carbon::parse($request->out_pay_date)->format('Y-m-d'),
                    'is_voucher' => 'Y',
                ]);

            // Check if ALL installments are now Paid → close the loan
            $remainingDue = EmpLoanDetail::where('loan_app_no', $loanAppNo)
                ->where('status', 'Due')
                ->count();

            // Update master: OUT_PAY_DATE, OUT_PAY_VOUCHER, IS_CLOSE
            EmpLoanMaster::where('loan_app_no', $loanAppNo)->update([
                'out_pay_date'    => Carbon::parse($request->out_pay_date)->format('Y-m-d'),
                'out_pay_voucher' => $request->out_pay_voucher ?? null,
                'is_close'        => $remainingDue === 0 ? 'Y' : 'N',
            ]);

            DB::commit();

            $details = EmpLoanDetail::where('loan_app_no', $loanAppNo)
                                     ->orderBy('install_no')->get()
                                     ->map(fn($d) => $this->formatDetail($d));

            return response()->json([
                'message'   => 'Payment processed. ' . $count . ' installment(s) marked Paid.',
                'is_closed' => $remainingDue === 0,
                'details'   => $details,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /* ══════════════════════════════════════════════════════════════════
       RESCHEDULE
       - Balance = PBEOM of last Paid row
       - New Due rows start after last Paid install_no
       - End-of-month date logic applied
    ══════════════════════════════════════════════════════════════════ */
    public function reschedule(Request $request): JsonResponse
    {
        $request->validate([
            'loan_app_no'    => 'required|exists:emp_loan_master,loan_app_no',
            'new_period'     => 'required|integer|min:1',
            'new_instt_date' => 'required|date',
            'company_id'     => 'required',
        ]);

        $loanAppNo = $request->loan_app_no;
        $newPeriod = (int) $request->new_period;
        $firstDate = Carbon::parse($request->new_instt_date);
        $companyId = $request->company_id;
        $isEndOfMonth = ($firstDate->day === (int) $firstDate->copy()->endOfMonth()->day);

        DB::beginTransaction();
        try {
            // Delete only Due rows
            EmpLoanDetail::where('loan_app_no', $loanAppNo)
                          ->where('status', 'Due')
                          ->delete();

            // Last Paid install number
            $maxPaid = EmpLoanDetail::where('loan_app_no', $loanAppNo)
                                     ->where('status', 'Paid')
                                     ->max('install_no') ?? 0;

            // Opening balance = PBEOM of last Paid row
            if ($maxPaid > 0) {
                $pbbom = (float) EmpLoanDetail::where('loan_app_no', $loanAppNo)
                    ->where('install_no', $maxPaid)
                    ->value('pbeom');
            } else {
                $pbbom = (float) EmpLoanMaster::where('loan_app_no', $loanAppNo)
                    ->value('sanction_amount');
            }

            $installAmt = $newPeriod > 0 ? round($pbbom / $newPeriod, 2) : 0;

            $rows = [];
            for ($i = 1; $i <= $newPeriod; $i++) {
                $instNo = $maxPaid + $i;

                if ($isEndOfMonth) {
                    $installDate = $firstDate->copy()
                        ->addMonthsNoOverflow($i - 1)
                        ->endOfMonth()->startOfDay();
                } else {
                    $installDate = $firstDate->copy()->addMonthsNoOverflow($i - 1);
                }

                $pbeom  = round($pbbom - $installAmt, 2);
                $rows[] = [
                    'loan_app_no'    => $loanAppNo,
                    'company_id'     => $companyId,
                    'install_no'     => $instNo,
                    'install_amount' => $installAmt,
                    'install_date'   => $installDate->format('Y-m-d'),
                    'pbbom'          => round($pbbom, 2),
                    'pbeom'          => $pbeom,
                    'status'         => 'Due',
                    'is_voucher'     => 'N',
                ];
                $pbbom = $pbeom;
            }

            if ($rows) EmpLoanDetail::insert($rows);

            // Update NEW_INSTT_DATE and NEW_PERIOD in master
            EmpLoanMaster::where('loan_app_no', $loanAppNo)->update([
                'new_period'     => $newPeriod,
                'new_instt_date' => $firstDate->format('Y-m-d'),
                'is_close'       => 'N',
            ]);

            DB::commit();

            // Return ALL rows (Paid + Due) ordered by install_no
            $details = EmpLoanDetail::where('loan_app_no', $loanAppNo)
                                     ->orderBy('install_no')->get()
                                     ->map(fn($d) => $this->formatDetail($d));

            return response()->json([
                'message' => 'Loan rescheduled successfully.',
                'details' => $details,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /* ══════════════════════════════════════════════════════════════════
       PREVIOUS BALANCE
    ══════════════════════════════════════════════════════════════════ */
    public function previousBalance(Request $request): JsonResponse
    {
        $empNo = $request->emp_no;

        $activeLoanIds = EmpLoanMaster::where('emp_no', $empNo)
            ->whereIn('loan_app_no', function ($q) {
                $q->select(DB::raw('DISTINCT loan_app_no'))
                  ->from('emp_loan_detail')
                  ->where('status', 'Due');
            })->pluck('loan_app_no');

        $previousSanction = EmpLoanMaster::whereIn('loan_app_no', $activeLoanIds)
            ->sum('sanction_amount');

        $preBalance = 0;
        foreach ($activeLoanIds as $id) {
            $maxPaidNo = EmpLoanDetail::where('loan_app_no', $id)
                                       ->where('status', 'Paid')
                                       ->max('install_no');
            if ($maxPaidNo) {
                $preBalance += (float) EmpLoanDetail::where('loan_app_no', $id)
                    ->where('install_no', $maxPaidNo)->value('pbeom');
            }
        }

        return response()->json([
            'previous_sanction_amount' => round((float) $previousSanction, 2),
            'pre_balance_amount'       => round($preBalance, 2),
        ]);
    }

    /* ══════════════════════════════════════════════════════════════════
       EMPLOYEES LIST  – uses get_dept_name() & get_section() DB functions
    ══════════════════════════════════════════════════════════════════ */
    public function employees(Request $request): JsonResponse
    {
        $query = DB::table('emp_personal as ep')
            ->join('emp_official as eo', 'ep.empno', '=', 'eo.empno')
            ->where('ep.status', 'Active')
            ->where('eo.company_id', $request->company_id)
            ->select(
                'ep.empno as emp_no',
                'ep.new_empno',
                DB::raw("HRM.GET_EMP_NAME(ep.empno) as emp_name"),
                'eo.dept_no',
                DB::raw("get_dept_name(eo.dept_no)  as dept_name"),
                'eo.section_no',
                DB::raw("get_section(eo.section_no) as section_name"),
                'eo.des_name',
                'eo.joining_date',
                'eo.gross'
            );

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                 $w->where('ep.new_empno', 'like', "%{$q}%")
          ->orWhere('ep.first_name', 'like', "%{$q}%")
          ->orWhere('ep.last_name', 'like', "%{$q}%");
            });
        }

        $employees = $query->orderBy('ep.empno')->limit(200)->get();

        return response()->json(['data' => $employees]);
    }

    /* ══════════════════════════════════════════════════════════════════
       DESTROY DUE DETAILS ONLY
       DELETE FROM EMP_LOAN_DETAIL
       WHERE LOAN_APP_NO = :b1 AND STATUS = 'Due'
       Keeps all Paid rows intact.
    ══════════════════════════════════════════════════════════════════ */
    public function destroyDue(string $loanAppNo): JsonResponse
    {
        EmpLoanMaster::findOrFail($loanAppNo); // 404 if not found

        DB::beginTransaction();
        try {
            $deleted = EmpLoanDetail::where('loan_app_no', $loanAppNo)
                ->where('status', 'Due')
                ->delete();

            // If no Due rows remain, reopen loan (IS_CLOSE stays as-is)
            EmpLoanMaster::where('loan_app_no', $loanAppNo)->update([
                'new_period'     => null,
                'new_instt_date' => null,
            ]);

            DB::commit();

            $details = EmpLoanDetail::where('loan_app_no', $loanAppNo)
                ->orderBy('install_no')->get()
                ->map(fn($d) => $this->formatDetail($d));

            return response()->json([
                'message' => $deleted . ' Due installment(s) deleted successfully.',
                'details' => $details,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    /* ══════════════════════════════════════════════════════════════════
       PRINT VIEW
    ══════════════════════════════════════════════════════════════════ */
    public function print(string $loanAppNo)
    {
        $loan = EmpLoanMaster::selectRaw("
        EMP_LOAN_MASTER.*,
        GET_EMP_NAME(emp_no) AS EMP_NAME,
        GET_DEPT_NAME(dept_no) AS DEPT_NAME,
        GET_SECTION(section_no) AS SECTION_NAME
    ")->with(['details'])->findOrFail($loanAppNo);
        return view('payroll.advance-loan.print', compact('loan'));
    }

    /* ══════════════════════════════════════════════════════════════════
       PRIVATE HELPERS
    ══════════════════════════════════════════════════════════════════ */
    private function validateMaster(Request $request): array
    {
        return $request->validate([
            // Exact Oracle column names (snake_case)
            'company_id'               => 'required|string|max:10',
            'emp_no'                   => 'required|string|max:100',
            'new_empno'                => 'nullable|string|max:20',
            'des_name'                 => 'nullable|string|max:100',
            'dept_no'                  => 'nullable|string|max:50',
            'dept_name'                => 'nullable|string|max:100',
            'section_no'               => 'nullable|string|max:50',
            'sec_name'                 => 'nullable|string|max:100',
            'gross_amount'             => 'nullable|numeric',
            'joining_date'             => 'nullable|date',
            'application_date'         => 'required|date',
            'loan_approved_date'       => 'nullable|date',
            'loan_type'                => 'nullable|string|max:50',
            'sanction_amount'          => 'required|numeric|min:0.01',
            'period'                   => 'required|integer|min:1',
            'monthly_installment'      => 'nullable|numeric',
            'first_install_date'       => 'nullable|date',
            'previous_sanction_amount' => 'nullable|numeric',
            'pre_balance_amount'       => 'nullable|numeric',
            'employe_name'             => 'nullable|string|max:100',
            'is_close'                 => 'nullable|in:Y,N',
        ]);
    }

    private function formatLoan(EmpLoanMaster $loan): array
    {
        return array_merge($loan->toArray(), [
            'application_date'   => $loan->application_date?->format('d-M-Y'),
            'loan_approved_date' => $loan->loan_approved_date?->format('d-M-Y'),
            'first_install_date' => $loan->first_install_date?->format('d-M-Y'),
            'joining_date'       => $loan->joining_date?->format('d-M-Y'),
            'out_pay_date'       => $loan->out_pay_date?->format('d-M-Y'),
            'new_instt_date'     => $loan->new_instt_date?->format('d-M-Y'),
            'details'            => $loan->details->map(fn($d) => $this->formatDetail($d)),
        ]);
    }

    private function formatDetail(EmpLoanDetail $d): array
    {
        return [
            'install_no'     => $d->install_no,
            'install_date'   => $d->install_date?->format('d-M-Y'),
            'install_amount' => $d->install_amount,
            'pbbom'          => $d->pbbom,
            'pbeom'          => $d->pbeom,
            'status'         => $d->status,
            'paydate'        => $d->paydate?->format('d-M-Y'),
            'company_id'     => $d->company_id,
            'is_voucher'     => $d->is_voucher,
        ];
    }
}
