<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerAccList extends Model
{
    use HasFactory;
    protected $table ='BUYER_ACCESORIES_LIST';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable=[
        'id_pk',
        'buyer_id',
        'price_date',
        'buyer_name',
    ];
}
