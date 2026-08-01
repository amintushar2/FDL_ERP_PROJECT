<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmpLoanDetail extends Model
{
    protected $table = 'emp_loan_detail';
    public $timestamps = false;

    protected $fillable = [
        'loan_app_no',
        'company_id',
        'install_no',
        'install_amount',
        'install_date',
        'pbbom',
        'pbeom',
        'status',
        'paydate',
        'is_voucher',
    ];

    protected $casts = [
        'install_date'   => 'date',
        'paydate'        => 'date',
        'install_amount' => 'decimal:2',
        'pbbom'          => 'decimal:2',
        'pbeom'          => 'decimal:2',
    ];

    public function master(): BelongsTo
    {
        return $this->belongsTo(EmpLoanMaster::class, 'loan_app_no', 'loan_app_no');
    }
}
