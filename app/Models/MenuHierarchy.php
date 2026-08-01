<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MenuHierarchy extends Model
{
    protected $table      = 'F_STORE.ALL_MENU_HIERARCHY';
    protected $primaryKey = 'MENU_CHILD_ID';
    public    $keyType    = 'string';
    public    $incrementing = false;
    public    $timestamps   = false;

    protected $fillable = [
        'TITLE','ITEM_TYPE','CHILD_ID','PARENT_ID','OBJECT_NAME',
        'DESCRIPTION','INSERT_DATE','UPDATE_BY','UPDATE_DATE',
        'FILE_NAME','SORT_BY','IS_ACTIVE','INSERT_BY','DIR',
    ];

    public function children()
    {
        return $this->hasMany(MenuHierarchy::class, 'PARENT_ID', 'CHILD_ID');
    }

    public function parent()
    {
        return $this->belongsTo(MenuHierarchy::class, 'PARENT_ID', 'CHILD_ID');
    }

    public function groupDetails()
    {
        return $this->hasMany(UserGroupDetail::class, 'MENU_ITEM_ID', 'CHILD_ID');
    }
}