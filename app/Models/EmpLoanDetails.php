<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpLoanDetails extends Model
{
    use HasFactory;


    protected $table='EMP_LOAN_DETAIL';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable =[
        'loan_app_no',
        'install_no',
 'install_amount',
 'install_date',
 'pbbom',
 'pbeom',
 'paydate',
 'status',
 'company_id',
 'is_voucher'
    ];
}
