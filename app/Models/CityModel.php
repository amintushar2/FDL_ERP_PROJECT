<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityModel extends Model
{
    use HasFactory;
    protected $table='CITY';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable=[
        'city',
        'CITY_ID'
    ];
}
