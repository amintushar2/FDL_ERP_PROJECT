<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdCardPrintLog extends Model
{
    protected $fillable = [
        'emp_no',
        'card_type',
        'report_file',
        'section_no',
        'from_date',
        'end_date',
        'printed_by',
    ];

    protected $casts = [
        'from_date' => 'date',
        'end_date'  => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_no', 'emp_no');
    }
}
