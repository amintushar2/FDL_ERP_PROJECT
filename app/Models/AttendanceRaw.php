<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRaw extends Model
{
    use HasFactory;

    protected $table ='ATND_RAW';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'mach_no',
        'card_no',
        'atnd_date',
        'atnd_time',
       
    ];
}
