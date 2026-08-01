<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * HrmReport — maps to HRM.HRM_REPORT (exact column names)
 *
 * Columns: report_id, report_file_name, report_title, is_buyer, module, js_report
 */
class HrmReport extends Model
{
    protected $table      = 'hrm_report';
    protected $primaryKey = 'report_id';
    public    $incrementing = false;
    protected $keyType    = 'decimal';

    protected $fillable = [
        'report_id',
        'report_file_name',
        'report_title',
        'is_buyer',
        'module',
        'js_report',
    ];

    protected $casts = [
        'report_id' => 'integer',
        'is_buyer'  => 'integer',
        'module'    => 'integer',
    ];

    // ── Relationships ────────────────────────────────────────────────

    public function reportParameters(): HasMany
    {
        return $this->hasMany(HrmReportParameter::class, 'report_id', 'report_id')
                    ->orderBy('serial_no');
    }

    // ── Scopes ───────────────────────────────────────────────────────

    /**
     * Mirrors Oracle query in WHEN-NEW-FORM-INSTANCE:
     *   SELECT REPORT_TITLE, to_char(REPORT_ID) REPORT_ID
     *   FROM HRM_REPORT
     *   WHERE IS_BUYER IN(0,1) AND MODULE=1
     *   ORDER BY 1 ASC
     */
    public function scopeForHrmModule($query, int $module = 1)
    {
        return $query->whereIn('is_buyer', [0, 1])
                     ->where('module', $module)
                     ->orderBy('report_title', 'asc');
    }
}
