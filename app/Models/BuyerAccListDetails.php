<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerAccListDetails extends Model
{
    use HasFactory;
    protected $table ='BUYER_ACCESORIES_LIST_DT';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable=[
        'id_pk',
        'item_id',
        'item_unit',
        'price',
        'supplier_id',
        'image_loc',
        'slno',
    ];
}
