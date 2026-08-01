<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignationModel extends Model
{
    use HasFactory;
    protected $table='DESIGNATION_DETAILS';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable =[
     'des_id',
     'designation_name',
     'in_short',
     'in_bengali'
    ];
}
