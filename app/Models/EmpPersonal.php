<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpPersonal extends Model
{
    protected $table = 'HRM.EMP_PERSONAL';
    public $timestamps = false;
    public $incrementing = false;
 protected $primaryKey = 'empno'; // or your actual PK
    protected $keyType = 'string'; // if empno is not an integer
    protected $fillable = [
        'empno',
        'card_no',
        'first_name',
        'middle_name',
        'last_name',
        'b_name',
        'gurdian_name',
        'dob',
        'age',
        'sex',
        'marial_status',
        'id_mark',
        'blood_group',
        'passport_no',
        'place_of_issue',
        'valid_till',
        'religion_name',
        'nationality_desc',
        'base',
        'ref',
        'ref2',
        'status',
        'father_name',
        'mother_name',
        'husband_name',
        'as_on',
        'age2',
        'hbs_test',
        'emp_mobile_no',
        'is_report',
        'national_id_no',
        'id_card_issue',
        'company_id',
        'sms_mobile_no',
        'new_empno',
        'birthday_id',
        'emp_pic',
        'last_education',
        'insert_by',
        'insert_date',
        'update_by',
        'update_date',
        'office_food',
        'per_section_id',
        'per_floor_id',
        'religion_id',
        'worker_type',
        'permanent_empno',
        'emp_img',
        'emp_sign',
        'update_user',
    ];
 
    // ─────── RELATIONSHIPS ───────
    public function getempofficial()
    {
        return $this->hasOne(EmpOfficial::class, 'empno', 'empno');
    }
 
    public function getemploc()
    {
        return $this->hasMany(EmpLocation::class, 'empno', 'empno');
    }
 
    public function empQualification()
    {
        return $this->hasMany(Emp_qualificationModel::class, 'empno', 'empno');
    }
 
    public function getEmpShortModel()
    {
        return $this->hasMany(Emp_ShortModel::class, 'empno', 'empno');
    }
 
    public function getEmpFamily()
    {
        return $this->hasMany(Emp_familyModel::class, 'empno', 'empno');
    }
 
    public function getEmpHistory()
    {
        return $this->hasMany(Emp_historyModel::class, 'empno', 'empno');
    }
 
    public function getEmpTraining()
    {
        return $this->hasMany(Emp_trainingModel::class, 'empno', 'empno');
    }
 
    public function getEmpWorkExp()
    {
        return $this->hasMany(Emp_work_expModel::class, 'empno', 'empno');
    }

    public function locationBangla()
    {
        return $this->hasOne(EmpLocationBangla::class, 'empno', 'empno');
    }

}

