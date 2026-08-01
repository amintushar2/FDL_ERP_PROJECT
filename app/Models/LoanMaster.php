<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LoanMaster extends Model
{
    use HasFactory;
    protected $table ='EMP_LOAN_MASTER';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'emp_no',
        'dept_no',
        'section_no',
        'gross_amount',
        'joining_date',
        'application_date',
        'loan_approved_date',
        'sanction_amount',
        'pre_balance_amount',
        'first_install_date',
        'period',
        'loan_app_no',
        'ref_des_name',
        'new_instt_date',
        'new_period',
        'company_id',
        'monthly_installment',
    
    ];

}
