<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GGatepassMaster extends Model
{

  
    use HasFactory;
    protected $fillable = [

        'pass_no' ,
        'pass_date' ,
       'party_id' 
     ];
}
