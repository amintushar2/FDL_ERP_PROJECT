<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmpLoanSetting extends Model
{
    protected $table = 'emp_loan_setting';

    protected $fillable = [
        'loan_app_no',
        'company_id',
        'from_instt',
        'to_instt',
        'loan_amount',
    ];

    protected $casts = [
        'loan_amount' => 'decimal:2',
    ];

    public function master(): BelongsTo
    {
        return $this->belongsTo(EmpLoanMaster::class, 'loan_app_no', 'loan_app_no');
    }
}
