<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryGroup extends Model
{
    use HasFactory;

        protected $table = 'F_STORE.INVENTORY_GROUP';
    protected $primaryKey = 'inv_group_id';

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;
}
