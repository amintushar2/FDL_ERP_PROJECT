<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubDetail extends Model
{
    protected $table    = 'F_STORE.ALL_USER_SUB_DETAILS';
    public    $timestamps = false;

    protected $fillable = [
        'menu_item_id',
        'user_group_id',
        'sub_menu_id',
        'sub_menu_1',
        'sub_menu_2',
        'sub_menu_name',
        'route',
        'enabled',
        'user_id',
    ];

    // ── Scopes ─────────────────────────────────────────────────────────────
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForGroup($query, $groupId)
    {
        return $query->where('user_group_id', $groupId);
    }

    // ── Relationships ───────────────────────────────────────────────────────
    public function group()
    {
        return $this->belongsTo(UserGroupMaster::class, 'user_group_id', 'user_group_id');
    }

    public function menu()
    {
        return $this->belongsTo(MenuHierarchy::class, 'menu_item_id', 'child_id');
    }
}
