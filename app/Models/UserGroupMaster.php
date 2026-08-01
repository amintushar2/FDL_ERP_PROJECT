<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroupMaster extends Model
{
    protected $table      = 'F_STORE.ALL_USER_GROUP_MASTER';
    protected $primaryKey = 'USER_GROUP_ID';
    public    $keyType    = 'string';
    public    $incrementing = false;
    public    $timestamps   = false;

    protected $fillable = [
        'USER_GROUP_ID','GROUP_TITLE','GROUP_DESCRIPTION',
        'INSERT_DATE','UPDATE_BY','UPDATE_DATE','INSERT_BY',
    ];

    public function users()
    {
        return $this->hasMany(UserInfo::class, 'USER_GROUP_ID', 'USER_GROUP_ID');
    }

    public function menuDetails()
    {
        return $this->hasMany(UserGroupDetail::class, 'USER_GROUP_ID', 'USER_GROUP_ID');
    }
}