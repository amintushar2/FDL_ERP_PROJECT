<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurOrderMaster extends Model
{
    use HasFactory;
    protected $table      = 'PUR_ORDER_MASTER';
    protected $primaryKey = 'PUR_ORDER_PK';
    public    $incrementing = false;
    public    $timestamps   = false;
 
    protected $fillable = [
        'PUR_ORDER_PK',
        'PUR_ORDER_NO',
        'PUR_ORDER_DATE',
        'SUPPLIER_NO',
        'CURRENCY_RATE',
        'PAY_TERMS',
        'ORDER_BY',
        'ORDER_BY_NUM',
        'PUR_REF_NO',
        'PUR_REQU_NO',
        'PUR_TYPE',
    ];
 
    protected $casts = [
        'PUR_ORDER_DATE' => 'datetime',
        'CURRENCY_RATE'  => 'decimal:4',
    ];
 
    // -------------------------------------------------------
    // PK generation — mirrors PRE-INSERT (PUR_ORDER_MASTER)
    // Format: 'PU-' || MMYYYY || LPAD(seq, 3, '0')
    // -------------------------------------------------------
    protected static function boot(): void
    {
        parent::boot();
 
        static::creating(function (self $model) {
            if (empty($model->PUR_ORDER_PK)) {
                $date    = $model->PUR_ORDER_DATE ?? now();
                $mmyyyy  = $date->format('mY');
                $row     = \DB::selectOne(
                    "SELECT NVL(MAX(TO_NUMBER(SUBSTR(PUR_ORDER_PK,10))),0)+1 AS nxt
                     FROM PUR_ORDER_MASTER
                     WHERE TO_CHAR(PUR_ORDER_DATE,'MMRRRR') = TO_CHAR(TO_DATE(:dt,'YYYY-MM-DD'),'MMRRRR')",
                    ['dt' => $date->format('Y-m-d')]
                );
                $seq     = str_pad($row->nxt ?? 1, 3, '0', STR_PAD_LEFT);
                $model->PUR_ORDER_PK = "PU-{$mmyyyy}{$seq}";
            }
 
            if (empty($model->PUR_ORDER_NO)) {
                $date   = $model->PUR_ORDER_DATE ?? now();
                $yyyymm = $date->format('Ym');
                $row    = \DB::selectOne(
                    "SELECT NVL(MAX(TO_NUMBER(SUBSTR(PUR_ORDER_NO,7))),0)+1 AS nxt
                     FROM PUR_ORDER_MASTER
                     WHERE TO_CHAR(PUR_ORDER_DATE,'MMRRRR') = TO_CHAR(TO_DATE(:dt,'YYYY-MM-DD'),'MMRRRR')",
                    ['dt' => $date->format('Y-m-d')]
                );
                $seq    = str_pad($row->nxt ?? 1, 3, '0', STR_PAD_LEFT);
                $model->PUR_ORDER_NO = "{$yyyymm}{$seq}";
            }
        });
    }
 
    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------
    public function styles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurOrderStyle::class, 'PUR_ORDER_PK', 'PUR_ORDER_PK');
    }
 
    public function details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurOrderDetails::class, 'PUR_ORDER_PK', 'PUR_ORDER_PK');
    }
 
    public function terms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurOrderTerms::class, 'PUR_ORDER_PK', 'PUR_ORDER_PK');
    }
 
    // -------------------------------------------------------
    // FORMULA-CALCULATION: SUPPLIER_NAME
    // :PUR_ORDER_MASTER.SUPPLIER_NAME := COM.GET_PARTY_NAME(SUPPLIER_NO)
    // -------------------------------------------------------
    public function getSupplierNameAttribute(): ?string
    {
        if (!$this->SUPPLIER_NO) {
            return null;
        }
        $row = \DB::selectOne(
            "SELECT PARTY_NAME FROM PARTY_PROFILE WHERE PARTY_ID = :id",
            ['id' => $this->SUPPLIER_NO]
        );
        return $row?->PARTY_NAME;
    }
}
