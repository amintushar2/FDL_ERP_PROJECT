<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineModel extends Model
{
    use HasFactory;
    protected $table='LINE_INFO';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable =[
        'line_no',
        'line',
        'line_in_bangla',
        'l_group'
    ];
}
