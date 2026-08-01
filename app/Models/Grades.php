<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grades extends Model
{
    use HasFactory;
     protected $table = 'HRM.GRADE';

    protected $primaryKey = 'GRADE_ID';

    public $incrementing = false; // VARCHAR2 → not auto-increment

    protected $keyType = 'string';

    public $timestamps = false; // no created_at / updated_at columns

    protected $fillable = [
        'GRADE_ID',
        'GRADE_NAME',
        'PAY_SCALE',
        'GRADE_TYPE',
        'HISAL',
        'LOSAL',
        'EMP_TYPE',
        'CURRENT_GROSS',
        'GRADE_BANGLA',
    ];
}
