<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emp_historyModel extends Model
{
    use HasFactory;
    protected $table='EMP_HISTRORY';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable =[
        'empno',
        'join_as',
        'work_location',
        'join_date',
        'designation'
    ];
}
