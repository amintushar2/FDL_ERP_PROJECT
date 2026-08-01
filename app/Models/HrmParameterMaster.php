<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * HrmParameterMaster — maps to HRM.HRM_PARAMETER_MASTER (exact columns)
 *
 * Columns: parameter_no, parameter_name, parameter_type, block_item, block_value_item
 */
class HrmParameterMaster extends Model
{
    protected $table      = 'hrm_parameter_master';
    protected $primaryKey = 'parameter_no';
    public    $incrementing = false;

    protected $fillable = [
        'parameter_no',
        'parameter_name',
        'parameter_type',
        'block_item',
        'block_value_item',
    ];
}
