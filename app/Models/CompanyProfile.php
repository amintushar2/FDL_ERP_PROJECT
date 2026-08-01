<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{

    protected $table='COMPANY';
    public $timestamps = false;
    public $incrementing = false;
            protected $primaryKey = 'company_id'; // ⭐ FIX

    use HasFactory;

    protected $fillable =[
      
        'company_id',
        'company_name',
        'in_bengali',
        'address',
        'address_bangla',
        'tel',
        'fax',
        'email',
        'logo_location',
    ];
}
