<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpPersonal extends Model
{
    protected $table='EMP_PERSONAL';
    public $timestamps = false;
    public $incrementing = false;
    use HasFactory;

    protected $fileable=[
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

    
    public function getempofficial(){

        return $this->hasMany('App\Models\EmpOfficial','empno','empno');
    }
    public function getemploc(){

        return $this->hasMany('App\Models\Emp_locationModel','empno','empno');
    }
    public function empQualification(){

        return $this->hasMany('App\Models\Emp_qualificationModel','empno','empno');
    }
    public function getEmpShortModel(){

        return $this->hasMany('App\Models\Emp_ShortModel','empno','empno');
    }
    public function getEmpFamily(){

        return $this->hasMany('App\Models\Emp_familyModel','empno','empno');
    }
    public function getEmpHistory(){

        return $this->hasMany('App\Models\Emp_historyModel','empno','empno');
    }
    public function getEmpTraining(){

        return $this->hasMany('App\Models\Emp_trainingModel','empno','empno');
    }
    public function getEmpWorkExp(){

        return $this->hasMany('App\Models\Emp_work_expModel','empno','empno');
    }
}

