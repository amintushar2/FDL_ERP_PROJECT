<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emp_qualificationModel extends Model
{
    use HasFactory;
    protected $table='EMP_QUALIFICATION';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable =[
        'empno',
        'name_of_ins',
        'passed_exam',
        'division',
        'year',
        'board',
        'marks',
        'subject'
    ];
    
}
