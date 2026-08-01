<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroupDetail extends Model
{
    protected $table    = 'F_STORE.ALL_USER_GROUP_DETAILS_WEB';
    public    $timestamps = false;

    protected $fillable = [
        'menu_item_id',
        'user_group_id',
        'enabled',
        'insert_date',
        'update_by',
        'update_date',
        'insert_by',
        'user_id',
    ];

    // Oracle returns lowercase — no mapping needed
    protected $casts = [
        'enabled' => 'string',
    ];

    // ── Scopes ────────────────────────────────────────────────────────────

    /** Group-level rows: user_id is null or empty */
    public function scopeGroupLevel($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('user_id')->orWhere('user_id', '');
        });
    }

    /** User-level override rows */
    public function scopeUserLevel($query, $userId = null)
    {
        $q = $query->whereNotNull('user_id')->where('user_id', '!=', '');
        if ($userId) {
            $q->where('user_id', $userId);
        }
        return $q;
    }

    /** Only enabled rows */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', 'Y');
    }

    // ── Relationships ──────────────────────────────────────────────────────
    public function menu()
    {
        return $this->belongsTo(MenuHierarchy::class, 'menu_item_id', 'child_id');
    }

    public function group()
    {
        return $this->belongsTo(UserGroupMaster::class, 'user_group_id', 'user_group_id');
    }

    public function user()
    {
        return $this->belongsTo(UserInfo::class, 'user_id', 'user_id');
    }
}
