<?php

namespace App\Services;

use App\Models\Accounts\VoucherMaster;
use App\Models\Accounts\VoucherDetail;
use App\Models\Accounts\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VoucherService
{
    // ── PRE-INSERT (AFM_VOUCHER_MASTER) ───────────────────────────
    public function beforeInsertMaster(array &$data): void
    {
        if (empty($data['VOUCHER_TYPE']))
            throw new \Exception('Voucher Type is required.');
        if (empty($data['VOUCHER_DATE']))
            throw new \Exception('Voucher Date is required.');
        if ($data['VOUCHER_TYPE'] === 'CASHP' && empty($data['CASH_ACCOUNT']))
            throw new \Exception('Cash Account is required for Cash Payment vouchers.');

        $no = $this->getVoucherNo($data['COMPANY_ID'], $data['VOUCHER_TYPE'], $data['VOUCHER_DATE']);

        $data['VOUCHER_NO']   = $no;
        $data['VOUCHER_ID']   = $no;
        $data['PREPARED_BY']  = $this->oracleUser();
        $data['INSERT_BY']    = $this->oracleUser();
        $data['ENTRY_STATUS'] = 'Complete';
        $data['AUTH_STAGE']   = 0;
        $data['MACHINE_NAME'] = request()->ip();
    }

    // ── PRE-UPDATE (AFM_VOUCHER_MASTER) ───────────────────────────
    public function beforeUpdateMaster(VoucherMaster $v): void
    {
        if ($v->IS_SUBMIT === 'Y')
            throw new \Exception('Cannot modify a submitted voucher.');
        if (!is_null($v->AUTH_STAGE) && $v->AUTH_STAGE != 4)
            throw new \Exception('Cannot modify a voucher under approval.');
        $v->UPDATE_BY = $this->oracleUser();
    }

    // ── PRE-DELETE ────────────────────────────────────────────────
    public function beforeDelete(VoucherMaster $v): void
    {
        if ($v->IS_SUBMIT === 'Y')
            throw new \Exception('Cannot delete a submitted voucher.');
    }

    // ── PRE-INSERT (AFM_VOUCHER_DETAIL) ───────────────────────────
    public function beforeInsertDetail(array &$data, string $voucherId): void
    {
        $row = DB::selectOne(
            'SELECT NVL(MAX(SL_NO),0)+1 AS next_sl FROM AFM_VOUCHER_DETAIL WHERE VOUCHER_ID=:id',
            ['id' => $voucherId]
        );
        $data['SL_NO']      = $row->next_sl;
        $data['VOUCHER_ID'] = $voucherId;

        if (!empty($data['CHE_RECP_NO']) && !empty($data['ACCOUNT_HEAD_ID'])) {
            DB::statement(
                "UPDATE AFM_CHEQUE_BOOK SET IS_ACTIVE='N', PAID_JOURNAL=:j
                 WHERE ACCOUNT_CODE=:a AND CHEQUE_NUMBER=:c",
                ['j' => $voucherId, 'a' => $data['ACCOUNT_HEAD_ID'], 'c' => $data['CHE_RECP_NO']]
            );
        }
    }

    // ── VALIDATE ACCOUNTING PERIOD ────────────────────────────────
    public function validateAccountingPeriod(string $date, string $type): void
    {
        if ($type !== 'POSDT' && Carbon::parse($date)->gt(Carbon::today()))
            throw new \Exception('Voucher date cannot be in the future.');

        $r = DB::selectOne(
            "SELECT COUNT(1) AS cnt FROM AFM_ACCOUNT_PERIOD_INFO
             WHERE :dt BETWEEN PERIOD_START_DATE AND PERIOD_END_DATE
             AND IS_PERIOD_CLOSED='N' AND IS_ACTIVE='Y'",
            ['dt' => Carbon::parse($date)->format('d-M-Y')]
        );
        if ((int)($r->cnt ?? 0) === 0)
            throw new \Exception('No open accounting period for the selected voucher date.');
    }

    // ── VALIDATE BALANCED TOTALS ──────────────────────────────────
    public function validateTotals(string $voucherId): void
    {
        $r = DB::selectOne(
            'SELECT SUM(DEBIT_AMOUNT) AS dr, SUM(CREDIT_AMOUNT) AS cr
             FROM AFM_VOUCHER_DETAIL WHERE VOUCHER_ID=:id',
            ['id' => $voucherId]
        );
        if (is_null($r->dr) || is_null($r->cr))
            throw new \Exception('Debit and Credit totals cannot be empty.');
        if (abs((float)$r->dr - (float)$r->cr) > 0.001)
            throw new \Exception(sprintf(
                'Debit (%.2f) and Credit (%.2f) totals must be equal.', $r->dr, $r->cr
            ));
    }

    // ── SUBMIT VOUCHER ────────────────────────────────────────────
    public function submitVoucher(VoucherMaster $v): void
    {
        $this->validateTotals($v->VOUCHER_ID);
        DB::transaction(function() use ($v) {
            $v->IS_SUBMIT    = 'Y';
            $v->IS_CHECK     = 'Y';
            $v->ENTRY_STATUS = 'Submited';
            $v->CHECHED_BY   = $this->oracleUser();
            $v->AUTH_STAGE   = 2;
            $v->save();
        });
    }

    // ── APPROVE VOUCHER ───────────────────────────────────────────
    public function approveVoucher(VoucherMaster $v): void
    {
        DB::transaction(function() use ($v) {
            $v->APPROVED_BY   = $this->oracleUser();
            $v->APPROVED_DATE = Carbon::now();
            $v->ENTRY_STATUS  = 'Approved';
            $v->IS_APPROVED   = 'Y';
            $v->AUTH_STAGE    = 4;
            $v->save();
        });
    }

    // ── FULL SAVE ─────────────────────────────────────────────────
    public function saveVoucher(array $masterData, array $lines): VoucherMaster
    {
        return DB::transaction(function() use ($masterData, $lines) {
            $this->beforeInsertMaster($masterData);
            $this->validateAccountingPeriod($masterData['VOUCHER_DATE'], $masterData['VOUCHER_TYPE']);
            $voucher = VoucherMaster::create($masterData);
            foreach ($lines as $line) {
                $this->beforeInsertDetail($line, $voucher->VOUCHER_ID);
                VoucherDetail::create($line);
            }
            $this->validateTotals($voucher->VOUCHER_ID);
            return $voucher->fresh();
        });
    }

    // ── HELPERS ───────────────────────────────────────────────────
    public function getVoucherNo(string $company, string $type, $date): string
    {
        $r = DB::selectOne(
            'SELECT AFM_PACK.GET_VOUCHER_NO(:c,:t,:d) AS no FROM DUAL',
            ['c' => $company, 't' => $type, 'd' => Carbon::parse($date)->format('d-M-Y')]
        );
        return $r->no;
    }

    public function getAccountFlags(string $accountId, string $companyId): array
    {
        $r = DB::selectOne(
            'SELECT IS_ASSET,IS_COST_CENTER,IS_CREDITOR,IS_DETOR,IS_EMPLOYEE,IS_LC
             FROM AFM_CHART_OF_ACCOUNTS WHERE ACCOUNT_HEAD_ID=:id AND COMPANY_ID=:c',
            ['id' => $accountId, 'c' => $companyId]
        );
        return $r ? (array)$r : [];
    }

    public function getAccountTitle(string $accountId, string $companyId): string
    {
        $r = DB::selectOne(
            'SELECT AFM_PACK.GET_ACCOUNT_TITLE(:id,:c) AS title FROM DUAL',
            ['id' => $accountId, 'c' => $companyId]
        );
        return $r->title ?? '';
    }

    public function getLedgerBalance(string $accountId, string $companyId): float
    {
        $r = DB::selectOne(
            'SELECT GET_AFM_LEDGER_BALANCE_NEW(:id,:c) AS bal FROM DUAL',
            ['id' => $accountId, 'c' => $companyId]
        );
        return (float)($r->bal ?? 0);
    }

    private function oracleUser(): string
    {
        return Auth::user()->oracle_username ?? Auth::user()->username ?? 'SYSTEM';
    }
}
