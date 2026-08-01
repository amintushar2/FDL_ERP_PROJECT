<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;


protected $table = 'F_STORE.UNIT_MEASUREMENT';
    protected $primaryKey = 'unit_id';

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

}
