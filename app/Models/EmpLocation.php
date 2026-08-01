<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpLocation extends Model
{
    use HasFactory;
    
    protected $table='EMP_LOCATION';
    protected $primaryKey = 'empno';
    public $timestamps = false;
    public $incrementing = false;


    protected $fillable =[
        'empno',
        'p_address',
        'p_city',
        'p_district',
        'p_pin_code',
        'p_phone',
        'p_fax',
        'p_cperson',
        'r_address',
        'r_city',
        'r_district',
        'r_pin_cod',
        'r_phone',
        'r_fax',
        'r_mobile',
        'r_email',
        'r_cperson',
        'p_village',
        'p_post_off',
        'p_police_station',
    ];
}
