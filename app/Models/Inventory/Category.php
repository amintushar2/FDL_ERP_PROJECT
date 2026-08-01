<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{



    use HasFactory;




protected $table = 'F_STORE.ITEM_CATEGORY';
    protected $primaryKey = 'category_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'category_name',
        'inv_group_id',
        'insert_by',
        'update_by'
    ];
   public function group()
    {
        return $this->belongsTo(
            \App\Models\Inventory\InventoryGroup::class,
            'inv_group_id',
            'inv_group_id'
        );
    }

    
}
