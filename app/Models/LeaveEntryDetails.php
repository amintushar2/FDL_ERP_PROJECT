<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveEntryDetails extends Model
{
    use HasFactory;

    protected $table='LEAVE_ENTRY_DETAILS';
    public $timestamps = false;
    public $incrementing = false;


    protected $fillable =[
'lv_cat_id',
'year',
'empno',
'leave_name',
'balance',
'application_no',
'application_date',
'approve_date',
'approve_days',
'approve_by',
'lv_from',
'lv_to',
'leave_id',
'max_days',
'pre_balance',
'remax',
'information',
'auto',
'lv_sl'
    ];


}


