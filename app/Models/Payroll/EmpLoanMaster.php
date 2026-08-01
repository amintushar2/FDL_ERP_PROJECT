<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmpLoanMaster extends Model
{
    protected $table      = 'emp_loan_master';
    protected $primaryKey = 'loan_app_no';
    public    $incrementing = false;
    protected $keyType    = 'string';
    public $timestamps = false;

    protected $fillable = [
        'loan_app_no',
        'company_id',
        'emp_no',
        'new_empno',
        'employe_name',
        'des_name',
        'dept_no',
        'dept_name',
        'section_no',
        'sec_name',
        'gross_amount',
        'joining_date',
        'ref_emp_no',
        'refference_name',
        'ref_des_name',
        'application_date',
        'loan_approved_date',
        'sanction_amount',
        'period',
        'monthly_installment',
        'first_install_date',
        'previous_sanction_amount',
        'pre_balance_amount',
        'out_from',
        'out_to',
        'out_amt',
        'out_pay_date',
        'new_period',
        'new_instt_date',
       'is_close',
    ];

    protected $casts = [
        'application_date'         => 'date',
        'loan_approved_date'       => 'date',
        'first_install_date'       => 'date',
        'joining_date'             => 'date',
        'out_pay_date'             => 'date',
        'new_instt_date'           => 'date',
        'sanction_amount'          => 'decimal:2',
        'monthly_installment'      => 'decimal:2',
        'gross_amount'             => 'decimal:2',
        'previous_sanction_amount' => 'decimal:2',
        'pre_balance_amount'       => 'decimal:2',
        'out_amt'                  => 'decimal:2',
    ];

    /* ── Relationships ──────────────────────────────────────────────── */

    public function details(): HasMany
    {
        return $this->hasMany(EmpLoanDetail::class, 'loan_app_no', 'loan_app_no')
                    ->orderBy('install_no');
    }

    public function settings(): HasMany
    {
        return $this->hasMany(EmpLoanSetting::class, 'loan_app_no', 'loan_app_no')
                    ->orderBy('from_instt');
    }

    /* ── Auto-generate loan_app_no  (mirrors Oracle PRE-INSERT trigger)
     *  Format: F-{COMPANY_ID}/{0001}
     * ────────────────────────────────────────────────────────────────*/
    public static function generateLoanAppNo(string $companyId): string
    {
        $max = self::where('company_id', $companyId)
        ->selectRaw("
            NVL(
                MAX(
                    TO_NUMBER(SUBSTR(loan_app_no, 8))
                ),
            0) as max_no
        ")
        ->value('max_no');

    return 'F-' . $companyId . '/' . str_pad($max + 1, 4, '0', STR_PAD_LEFT);
    }
}
