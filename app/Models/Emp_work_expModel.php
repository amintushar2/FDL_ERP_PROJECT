<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emp_work_expModel extends Model
{
    use HasFactory;
    protected $table='EMP_WORK_EXP';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable =[
        'empno',
        'organization',
        'd_from',
        'd_to',
        'leave_reason',
        'prv_emp_no',
        'org_address',
        'org_tel',
        'last_sal_drawn',
        'total_days',
        'designation'
    ];
}
