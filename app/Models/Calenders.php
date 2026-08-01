<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calenders extends Model
{
    use HasFactory;
    protected $table = 'HRM.CALENDER_MASTER';

    protected $primaryKey = 'CAL_CODE';

    public $incrementing = false; // VARCHAR2 → not auto-increment

    protected $keyType = 'string';

    public $timestamps = false; // no created_at / updated_at

    protected $fillable = [
        'CAL_CODE',
        'CALENDER_FOR',
        'MONTH',
        'YEAR',
        'SET_ON',
        'REMARKS',
        'IS_CLOSE',
    ];

    protected $casts = [
        'YEAR' => 'integer',
        'SET_ON' => 'datetime',
    ];
}
