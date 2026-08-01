<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurOrderDetails extends Model
{
    use HasFactory;
    protected $table      = 'PUR_ORDER_DETAILS';
    public    $timestamps = false;
 
    protected $fillable = [
        'PUR_ORDER_PK',
        'PO_NUMBER_ID',
        'PO_NUMBER',
        'SERIAL_NO',
        'ITEM_ID',
        'ITM_UNIT',
        'QUANTITY',
        'PERCENTAGE',
        'ITEM_RATE',
        'CURRENCY_CODE',
        'REMARKS',
        // virtual / computed (not stored):
        // ITEM_NAME   → FORMULA-CALCULATION via COM.GET_ITEM_NAME
        // ITEM_VALUE  → FORMULA: QUANTITY * ITEM_RATE
        // ITEM_VALUE_BDT → ITEM_VALUE * CURRENCY_RATE
        // MEAUSER    → unit of measure LOV
        // CHECK_DUPLICATE → runtime flag
    ];
 
    protected $casts = [
        'SERIAL_NO'  => 'integer',
        'QUANTITY'   => 'decimal:4',
        'PERCENTAGE' => 'decimal:2',
        'ITEM_RATE'  => 'decimal:4',
    ];
 
    // -------------------------------------------------------
    // PRE-INSERT: auto-generate SERIAL_NO
    // SELECT NVL(MAX(SERIAL_NO),0)+1 FROM PUR_ORDER_DETAILS WHERE PUR_ORDER_PK=:b1
    // -------------------------------------------------------
    protected static function boot(): void
    {
        parent::boot();
 
        static::creating(function (self $model) {
            if (empty($model->SERIAL_NO)) {
                $row = \DB::selectOne(
                    "SELECT NVL(MAX(SERIAL_NO),0)+1 AS nxt FROM PUR_ORDER_DETAILS WHERE PUR_ORDER_PK = :pk",
                    ['pk' => $model->PUR_ORDER_PK]
                );
                $model->SERIAL_NO = $row->nxt ?? 1;
            }
        });
    }
 
    // -------------------------------------------------------
    // FORMULA-CALCULATION: ITEM_NAME
    // COM.GET_ITEM_NAME(:ITEM_ID)
    // -------------------------------------------------------
    public function getItemNameAttribute(): ?string
    {
        if (!$this->ITEM_ID) {
            return null;
        }
        $row = \DB::selectOne(
            "SELECT ITEM_NAME FROM ITEM_MASTER WHERE ITEM_ID = :id",
            ['id' => $this->ITEM_ID]
        );
        return $row?->ITEM_NAME;
    }
 
    // -------------------------------------------------------
    // FORMULA-CALCULATION: ITEM_VALUE = QUANTITY * ITEM_RATE
    // POST-CHANGE (PUR_ORDER_DETAILS.QUANTITY / ITEM_RATE)
    // -------------------------------------------------------
    public function getItemValueAttribute(): float
    {
        return round(($this->QUANTITY ?? 0) * ($this->ITEM_RATE ?? 0), 4);
    }
 
    // -------------------------------------------------------
    // WHEN-LIST-CHANGED: CURRENCY_CODE
    // ITEM_VALUE_BDT = ITEM_VALUE * CURRENCY_RATE  (or ITEM_VALUE if same currency)
    // -------------------------------------------------------
    public function getItemValueBdtAttribute(): float
    {
        $rate = optional($this->master)->CURRENCY_RATE ?? 1;
        return round($this->item_value * $rate, 2);
    }
 
    // -------------------------------------------------------
    // WHEN-VALIDATE-ITEM (PUR_ORDER_DETAILS.ITEM_ID)
    // Check duplicate: COUNT(*) WHERE PUR_ORDER_PK & PO_NUMBER & ITEM_ID
    // -------------------------------------------------------
    public function isDuplicate(): bool
    {
        $count = \DB::selectOne(
            "SELECT COUNT(*) AS cnt FROM PUR_ORDER_DETAILS
             WHERE PUR_ORDER_PK = :pk AND PO_NUMBER = :po AND ITEM_ID = :item",
            [
                'pk'   => $this->PUR_ORDER_PK,
                'po'   => $this->PO_NUMBER,
                'item' => $this->ITEM_ID,
            ]
        );
        return ($count->cnt ?? 0) > 0;
    }
 
    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------
    public function master(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PurOrderMaster::class, 'PUR_ORDER_PK', 'PUR_ORDER_PK');
    }
 
    public function style(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PurOrderStyle::class, 'PO_NUMBER_ID', 'PO_NUMBER_ID');
    }
}
