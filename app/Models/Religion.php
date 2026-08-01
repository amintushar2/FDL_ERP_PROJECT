<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Religion extends Model
{
    use HasFactory;
     protected $table = 'HRM.RELIGION';

    protected $primaryKey = 'RELIGION_ID';

    public $incrementing = false; // since not defined as auto-increment

    protected $keyType = 'int';

    public $timestamps = false; // Oracle table has no created_at / updated_at

    protected $fillable = [
        'RELIGION_ID',
        'RELIGION_NAME',
    ];
}
