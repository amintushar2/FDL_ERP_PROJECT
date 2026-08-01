<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table ='ATTENDANCE_DETAILS';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'att_date',
        'empno',
        'in_time',
        'in_time2',
        'late',
        'status',
        'status2',
        'out_time',
        'out_time2',
        'othour',
        'othour2',
        'extraot',
        'late_extra',
        'empno_new',
    ];
}
