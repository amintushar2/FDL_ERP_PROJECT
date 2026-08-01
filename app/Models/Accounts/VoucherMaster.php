<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VoucherMaster extends Model
{
    protected $connection = 'oracle';
    protected $table      = 'AFM_VOUCHER_MASTER';
    protected $primaryKey = 'VOUCHER_ID';
    public    $incrementing = false;
    public    $keyType = 'string';
    public    $timestamps = false;

    protected $fillable = [
        'VOUCHER_ID',
        'VOUCHER_NO',
        'VOUCHER_TYPE',       // CASHP, BANKP, CASHR, BANKR, JOURL, POSDT
        'VOUCHER_DATE',
        'COMPANY_ID',
        'RECEIVE_PAY_TO',
        'CASH_ACCOUNT',
        'ENTRY_STATUS',       // Complete, Submited, Approved
        'AUTH_STAGE',         // 0,2,4
        'IS_SUBMIT',          // Y/N
        'IS_APPROVED',        // Y/N
        'IS_CHECK',           // Y/N
        'PREPARED_BY',
        'PREPARED_NAME',
        'CHECHED_BY',
        'APPROVED_BY',
        'APPROVED_DATE',
        'UPDATE_BY',
        'MACHINE_NAME',
        'INSERT_BY',
        'PAY_RECEIVE',        // computed: 'Pay To' / 'Received From'
        'AMOUNT_WORD',        // spelled-out total debit
    ];

    protected $casts = [
        'VOUCHER_DATE'   => 'date',
        'APPROVED_DATE'  => 'datetime',
        'AUTH_STAGE'     => 'integer',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function details(): HasMany
    {
             return $this->hasMany(VoucherDetail::class, 'VOUCHER_ID', 'VOUCHER_ID');

    }

    public function company()
    {
              return $this->belongsTo(CompanyProfile::class, 'COMPANY_ID', 'COMPANY_ID');

    }

    // ── Computed helpers ───────────────────────────────────────────

    /**
     * Returns 'Pay To' or 'Received From' based on voucher type.
     * Mirrors the FORMULA-CALCULATION on PAY_RECEIVE.
     */
    public function getPayReceiveLabelAttribute(): string
    {
        return match (strtoupper($this->VOUCHER_TYPE)) {
            'CASHP', 'BANKP' => 'Pay To',
            'CASHR', 'BANKR' => 'Received From',
            default          => '',
        };
    }

    public function getTotalDebitAttribute(): float
    {
        return (float) $this->details()->sum('DEBIT_AMOUNT');
    }

    public function getTotalCreditAttribute(): float
    {
        return (float) $this->details()->sum('CREDIT_AMOUNT');
    }

    public function isBalanced(): bool
    {
        return $this->total_debit === $this->total_credit;
    }
}
