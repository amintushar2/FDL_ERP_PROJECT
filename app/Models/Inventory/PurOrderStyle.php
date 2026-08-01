<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurOrderStyle extends Model
{
    use HasFactory;
    protected $table      = 'PUR_ORDER_STYLE';
    public    $timestamps = false;
 
    protected $fillable = [
        'PUR_ORDER_PK',
        'PO_NUMBER_ID',
        'PO_NUMBER',
        'STYLE_NO',     // ORDER_NO
        'ORDER_NO',
        'PO_QTY',
        'BUER_NAME',    // buyer name (formula field)
    ];
 
    protected $casts = [
        'PO_QTY' => 'decimal:2',
    ];
 
    // -------------------------------------------------------
    // FORMULA-CALCULATION: PO_NUMBER
    // GET_PO_NUMBER_ID(:PUR_ORDER_STYLE.PO_NUMBER_ID)
    // -------------------------------------------------------
    public function getPoNumberAttribute(): ?string
    {
        if (!$this->PO_NUMBER_ID) {
            return null;
        }
        $row = \DB::selectOne(
            "SELECT PO_NUMBER FROM ORDER_PO_NUMBER WHERE PO_NUMBER_ID = :id",
            ['id' => $this->PO_NUMBER_ID]
        );
        return $row?->PO_NUMBER;
    }
 
    // -------------------------------------------------------
    // FORMULA-CALCULATION: BUER_NAME
    // COM.GET_ORDER_BUYER(:PUR_ORDER_STYLE.ORDER_NO)
    // -------------------------------------------------------
    public function getBuerNameAttribute(): ?string
    {
        if (!$this->ORDER_NO) {
            return null;
        }
        $row = \DB::selectOne(
            "SELECT COM.GET_PARTY_NAME(M.BUYER_ID) BUYER_NAME
             FROM ORDER_MASTER M WHERE M.ORDER_NO_ID = :id",
            ['id' => $this->ORDER_NO]
        );
        return $row?->BUYER_NAME;
    }
 
    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------
    public function master(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PurOrderMaster::class, 'PUR_ORDER_PK', 'PUR_ORDER_PK');
    }
 
    public function details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurOrderDetails::class, 'PO_NUMBER_ID', 'PO_NUMBER_ID')
                    ->where('PUR_ORDER_PK', $this->PUR_ORDER_PK);
    }
}
