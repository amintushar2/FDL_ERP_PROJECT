<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictModel extends Model
{
    use HasFactory;
    protected $table='District';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable =[
        'district','district_id'
    ];
}
