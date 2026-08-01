<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emp_trainingModel extends Model
{
    use HasFactory;
    protected $table='EMP_TRAINING';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable =[
        'empno',
        't_title',
        't_conducted_by',
        't_from',
        't_to',
        't_certificate',
        'skill_type',
        'to_days'
    ];
}
