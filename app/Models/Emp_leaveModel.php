<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emp_leaveModel extends Model
{
    use HasFactory;
    protected $table='EMP_EXTRA';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable =[
        'empno',
        'earn_leave_balance',
        'earn_leave_balance_date',
        'earn_leave_next-schedule',
        'earn_leave_previous_balance',
        'cal_year'
    ];
}
