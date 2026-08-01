<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emp_familyModel extends Model
{
    use HasFactory;
    protected $table='EMP_FAMILY';
    public $timestamps = false;
protected $primaryKey = null;
public $incrementing = false;
    protected $fillable =[
           'empno',
    'depd_no',
    'depd_name',
    'relationship',
    'd_dob',
    'd_age',
    'd_sex',
    'd_as_on',
    'percentage',
    'address',
    'depent_name_bangla',
    'relation_bn',
    'address_bn',
    'village_bn',
    'po_bn',
    'ps_bn',
    'district_bn'
    ];
}
