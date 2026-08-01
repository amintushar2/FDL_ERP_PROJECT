<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emp_ShortModel extends Model
{
    use HasFactory;
    protected $table='EMP_SHORT_COURSE';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable =[
        'empno',
        'course_name',
        'conducted_by',
        'c_from',
        'c_to',
        'certificate',
        'total_day'
    ];
}
