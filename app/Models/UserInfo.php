<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class UserInfo extends Model
{
    protected $table      = 'F_STORE.ALL_USER_INFO';
    protected $primaryKey = 'USER_ID';
    public    $keyType    = 'string';
    public    $incrementing = false;
    public    $timestamps   = false;

    protected $fillable = [
        'USER_ID','EMPLOYEE_ID','USER_STATUS','USER_GROUP_ID',
        'INSERT_DATE','UPDATE_BY','UPDATE_DATE','USER_ROLE',
        'INITIAL_PASSWORD','INSERT_BY','USER_TYPE','CREDTI_LIMIT',
        'COMPANY_ID','USER_MOBILE',
    ];

    protected $hidden = ['INITIAL_PASSWORD'];

    public function group()
    {
        return $this->belongsTo(UserGroupMaster::class, 'USER_GROUP_ID', 'USER_GROUP_ID');
    }

    public function menuPermissions()
    {
        return $this->hasMany(UserGroupDetail::class, 'USER_ID', 'USER_ID');
    }
}
