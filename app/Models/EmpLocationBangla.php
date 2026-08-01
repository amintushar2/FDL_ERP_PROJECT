<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpLocationBangla extends Model
{
    use HasFactory;
    
    protected $table = 'HRM.EMP_LOCATION_BANGLA';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'empno';
protected $keyType = 'string';
    protected $fillable = [
        'empno',
        'father_name',
        'mother_name',
        'present_village',
        'present_psot',
        'present_thana',
        'present_dist',
        'permanent_village',
        'parmaent_post',
        'permanent_thana',
        'permanent_dist',
        'sopuse_name',
        'worker_class',
        'working_type',
        'new_empno'
    ];

    // Relationship to EmpPersonal
    public function empPersonal()
    {
        return $this->belongsTo(EmpPersonal::class, 'empno', 'empno');
    }

    // Relationship to English Location
    public function locationEnglish()
    {
        return $this->hasOne(EmpLocation::class, 'empno', 'empno');
    }
}
