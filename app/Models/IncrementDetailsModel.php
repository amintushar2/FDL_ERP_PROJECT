<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncrementDetailsModel extends Model
{
    use HasFactory;
    protected $table='INCREMENT_INFO';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable =[
        'empno',
        'emp_name',
        'section',
        'prev_designation',
        'cur_designation',
        'prev_ot_ent',
        'cur_ot_ent',
        'prev_gross',
        'incr_type',
        'increment_amt',
        'cur_gross',
        'incr_date',
        'remark_text',
        'prev_house_rent',
        'prev_medical',
        'prev_basic',
        'cur_basic',
        'cur_medical',
        'prev_medical',
        'cur_house_rent',
        'effective_date',

    ];
}
