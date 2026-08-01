<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoucherDetail extends Model
{
    protected $connection = 'oracle';
    protected $table      = 'AFM_VOUCHER_DETAIL';
    protected $primaryKey = null;        // Composite key: VOUCHER_ID + SL_NO
    public    $incrementing = false;
    public    $timestamps = false;

    protected $fillable = [
        'VOUCHER_ID',
        'SL_NO',
        'ACCOUNT_HEAD_ID',
        'ACCOUNT_NAME',       // formula-calculated via AFM_PACK.GET_ACCOUNT_TITLE
        'COMPANY_ID',
        'DEBIT_AMOUNT',
        'CREDIT_AMOUNT',
        'USD_DEBIT',
        'USD_CREDIT',
        'REFERENCE_NO',
        // Optional sub-fields (shown/hidden by account type flags)
        'CREDITOR_CODE',
        'CREDITOR_NAME',      // formula-calculated via COM.GET_PARTY_NAME
        'DETOR_CODE',
        'DETOR_NAME',         // formula-calculated via COM.GET_PARTY_NAME
        'EMP_CODE',
        'NEW_EMPNO',
        'EMP_NAME',           // formula-calculated via COM.GET_EMP_NAME
        'ASSET_CODE',
        'COST_CENTER',
        'LC_NO',
        'MASTER_LC_NO',
        'CHE_RECP_NO',        // Cheque / Receipt number
        'CHEQUE_DATE',
        'ASSET_ID',
    ];

    protected $casts = [
        'SL_NO'          => 'integer',
        'DEBIT_AMOUNT'   => 'decimal:4',
        'CREDIT_AMOUNT'  => 'decimal:4',
        'USD_DEBIT'      => 'decimal:4',
        'USD_CREDIT'     => 'decimal:4',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(VoucherMaster::class, 'VOUCHER_ID', 'VOUCHER_ID');
    }

    public function accountHead()
    {
        return $this->belongsTo(ChartOfAccount::class, 'ACCOUNT_HEAD_ID', 'ACCOUNT_HEAD_ID');
    }
}
