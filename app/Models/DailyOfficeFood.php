<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyOfficeFood extends Model
{
    protected $connection = 'oracle';

    protected $table = 'DAILLY_OOFICE_FOOD';

    // No single-column primary key in the source table.
    public $incrementing = false;
    protected $primaryKey = null;

    // The table has no CREATED_AT / UPDATED_AT columns.
    public $timestamps = false;

    protected $fillable = [
        'EMPNO',
        'NEW_EMPNO',
        'EMP_NAME',
        'ATT_DATE',
        'STATUS',
        'IS_FOOD',
    ];

    protected $casts = [
        'ATT_DATE' => 'date:Y-m-d',
    ];
}
