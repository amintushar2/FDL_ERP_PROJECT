<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftInfo extends Model
{
    use HasFactory;
        protected $table = 'HRM.SHIFT_INFO';

    protected $primaryKey = 'SHIFT_CODE';

    public $incrementing = false; // VARCHAR2 → not auto-increment

    protected $keyType = 'string';

    public $timestamps = false; // no created_at / updated_at

    protected $fillable = [
        'SHIFT_CODE',
        'SHIFT_NAME',
        'IN_TIME',
        'OUT_TIME',
        'GRACE_PERIOD',
        'MEAL_TIME',
        'SIN_TIME',
        'SOUT_TIME',
        'OT_LIMIT',
        'SHIFT_IN_TIME',
        'GRACE_PERIOD_2',
        'OT_LIMIT_3',
        'IS_ACTIVE',
    ];

    protected $casts = [
        'IN_TIME' => 'float',
        'OUT_TIME' => 'float',
        'GRACE_PERIOD' => 'float',
        'MEAL_TIME' => 'float',
        'OT_LIMIT' => 'float',
        'SHIFT_IN_TIME' => 'datetime',
        'GRACE_PERIOD_2' => 'integer',
        'OT_LIMIT_3' => 'integer',
    ];
}
