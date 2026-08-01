<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Section;

class EmpOfficial extends Model
{
    use HasFactory;
    protected $table='EMP_Official';
        protected $primaryKey = 'empno'; // ⭐ FIX
    protected $keyType = 'string'; // use 'int' if empno is numeric

    public $timestamps = false;
    public $incrementing = false;


    protected $fillable =[
        'empno',
        'company_id',
        'company_name',
       'dept_no',
       'dept_name',
       'section_no',
       'section_name',
       'emp_type',
       'grade_id',
       'grade_name',
       'joining_date',
       'work_ent',
       'ot_ent',
       'tran_ent',
       'pes_ent',
       'tax_ent',
       'pf_ent',
       'termination_date',
       'resigned_date',
       'weekly_off',
       'bank_name',

'res_ent',
'conform_date',
       'tin_no',
       'ac_no',
       'cal_code',
       'shift_code',
       's_group_name',
       'lv_cat_id',
       'gross',
       'shift_name',
       'des_id',
       'des_name',
       'floor_id',
       'floor_desc',
       'line',
       'line_info',
       'piece_rate',
       'pro_fund',
       'shift_rostering',
       'last_shift_roster_date',
       'last_shift_roster_direction',
       'last_shift_roster_direction',
       'bank_ac_no',
       'tax_deduction',
       'service_book_number',
       'other_allowance',
       'insert_by',
       'update_by',
       'update_user',
       'is_lefty'

    ];
    public function section()
    {
        return $this->belongsTo(
            Section::class,
            'section_no',
            'section_no'
        );
    }
}
