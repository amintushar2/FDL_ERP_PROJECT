<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * PayrollReport — maps to HRM.HRM_REPORT (same table as HRM reports)
 *
 * KEY DIFFERENCE from HrmReport:
 *   scopeForPayrollModule() uses MODULE=2 (HRM uses MODULE=1)
 *
 * Oracle source (.fmx WHEN-NEW-FORM-INSTANCE):
 *   SELECT REPORT_TITLE, to_char(REPORT_ID) REPORT_ID
 *   FROM HRM_REPORT
 *   WHERE IS_BUYER IN(0,1) AND MODULE=2   ← MODULE=2 for Payroll
 *   ORDER BY 1 ASC
 */
class PayrollReport extends Model
{
    protected $table        = 'hrm_report';   // Same Oracle table — filtered by MODULE=2
    protected $primaryKey   = 'report_id';
    public    $incrementing = false;
    protected $keyType      = 'decimal';

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

    // ── Relationships ─────────────────────────────────────────────────

    public function reportParameters(): HasMany
    {
        return $this->hasMany(HrmReportParameter::class, 'report_id', 'report_id')
                    ->orderBy('serial_no');
    }

    // ── Scopes ────────────────────────────────────────────────────────

    /**
     * Oracle .fmx: AND MODULE=2 (Payroll module)
     */
    public function scopeForPayrollModule($query)
    {
        return $query->whereIn('is_buyer', [0, 1])
                     ->where('module', 2)               // ← MODULE=2 (Payroll)
                     ->orderBy('report_title', 'asc');
    }
}
