<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{

    protected $table='COMPANY_PROFILE';
    public $timestamps = false;
    public $incrementing = false;
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
