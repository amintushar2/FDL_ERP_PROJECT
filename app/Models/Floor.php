<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;
     protected $table = 'HRM.FLOOR';

    protected $primaryKey = 'FLOOR_ID';

    public $incrementing = false; // VARCHAR2 → not auto-increment

    protected $keyType = 'string';

    public $timestamps = false; // no created_at / updated_at columns

    protected $fillable = [
        'FLOOR_ID',
        'FLOOR_DESC',
        'FLOOR_LOC',
        'COMPANY_ID',
    ];
}
