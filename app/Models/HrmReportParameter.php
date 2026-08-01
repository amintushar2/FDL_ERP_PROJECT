<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * HrmReportParameter — maps to HRM.HRM_REPORT_PARAMETER (exact columns)
 *
 * Columns: parameter_no, parameter_name, p_block_name, report_id, serial_no
 */
class HrmReportParameter extends Model
{
    protected $table = 'hrm_report_parameter';

    protected $fillable = [
        'parameter_no',
        'parameter_name',
        'p_block_name',
        'report_id',
        'serial_no',
    ];

    protected $casts = [
        'parameter_no' => 'integer',
        'report_id'    => 'integer',
        'serial_no'    => 'integer',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(HrmReport::class, 'report_id', 'report_id');
    }

    public function parameterMaster(): BelongsTo
    {
        return $this->belongsTo(HrmParameterMaster::class, 'parameter_no', 'parameter_no');
    }
}
