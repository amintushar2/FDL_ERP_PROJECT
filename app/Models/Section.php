<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    protected $table = 'HRM.SECTION';

    protected $primaryKey = 'SECTION_NO';

    public $incrementing = false; // it's a string (VARCHAR2)

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'SECTION_NO',
        'SECTION_NAME',
        'IN_BENGALI',
        'DEPT_NO',
        'DEPT_NAME',
        'COMPANY_ID',
    ];
}
