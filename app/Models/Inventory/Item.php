<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
 protected $table = 'F_STORE.ITEM_MASTER';
    protected $primaryKey = 'item_id';

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'item_name',
        'brand_id',
        'category_id',
        'unit_id',
        'credit_limit',
        'present_balance',
        'item_status',
        'insert_by',
        'update_by'
    ];

    public function category()
    {
        return $this->belongsTo(
            \App\Models\Inventory\Category::class,
            'category_id',
            'category_id'
        );
    }
    
public function unit()
{
    return $this->belongsTo(
        \App\Models\Inventory\Unit::class,
        'unit_id',
        'unit_id'
    );
}


}
