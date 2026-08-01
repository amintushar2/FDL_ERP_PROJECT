<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryModel extends Model
{
    use HasFactory;

    protected $table ='EMP_PAYMENT';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'basic',
        'company_id',
        'convance',
        'des_name',
        'empno',
        'food_allowance',
        'emp_grade',
        'gross',
        'new_empno',
        'stamp',
        'tax',
        'hr_amt'
    ];
}
