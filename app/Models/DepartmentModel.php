<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentModel extends Model
{
    use HasFactory;
    protected $table='DEPT';
    public $timestamps = false;
    public $incrementing = false;
            protected $primaryKey = 'dept_no'; // ⭐ FIX

    protected $fillable =[
        'dept_no',
        'dept_name',
        'in_bengali',
        'in_short',
        'c_name',
        'company_id'
    ];

}
