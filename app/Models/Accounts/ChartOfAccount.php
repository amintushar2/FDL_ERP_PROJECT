<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    protected $connection = 'oracle';
    protected $table      = 'AFM_CHART_OF_ACCOUNTS';
    protected $primaryKey = 'ACCOUNT_HEAD_ID';
    public    $incrementing = false;
    public    $keyType = 'string';
    public    $timestamps = false;

    protected $fillable = [
        'ACCOUNT_HEAD_ID',
        'ACCOUNT_HEAD_NAME',
        'COMPANY_ID',
        // Flags that control which sub-fields are visible in the UI
        'IS_ASSET',        // 1 = requires ASSET_CODE
        'IS_COST_CENTER',  // 1 = requires COST_CENTER
        'IS_CREDITOR',     // 1 = requires CREDITOR_CODE
        'IS_DETOR',        // 1 = requires DETOR_CODE
        'IS_EMPLOYEE',     // 1 = requires EMP_CODE
        'IS_LC',           // 1 = requires LC_NO
        'IS_MASTER_LC',    // 1 = requires MASTER_LC_NO
    ];
}
