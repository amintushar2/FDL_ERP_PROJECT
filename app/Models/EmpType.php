<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpType extends Model
{
    use HasFactory;
    protected $table = 'HRM.EMP_TYPE';

    protected $primaryKey = 'EMP_TYPE';

    public $incrementing = false; // VARCHAR2 → not auto-increment

    protected $keyType = 'string';

    public $timestamps = false; // no created_at / updated_at

    protected $fillable = [
        'EMP_TYPE',
        'TYPE_SET',
        'PRIORITY',
    ];
}
