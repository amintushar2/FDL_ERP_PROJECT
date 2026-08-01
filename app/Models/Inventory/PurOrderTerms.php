<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurOrderTerms extends Model
{
    use HasFactory;
    protected $table      = 'PUR_ORDER_TERMS';
    public    $timestamps = false;
 
    protected $fillable = [
        'PUR_ORDER_PK',
        'TERMS_ID',
        'TERMES',   // note: column name in Oracle is TERMES (typo in original schema)
    ];
 
    public function master(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PurOrderMaster::class, 'PUR_ORDER_PK', 'PUR_ORDER_PK');
    }
 
    /**
     * Mirrors the WHEN-BUTTON-PRESSED (PO_CHECK.PB_MAIN) logic that
     * auto-inserts standard terms when PO is committed.
     *
     * Original trigger:
     *   SELECT TERMS_ID, TERMS FROM PURCHASE_TERMS WHERE IS_STANDARD='Y'
     *   DELETE FROM PUR_ORDER_TERMS WHERE PUR_ORDER_PK=:b1 AND TERMS_ID IN (...)
     *   then re-inserts
     */
    public static function syncStandardTerms(string $purOrderPk): void
    {
        $standardTerms = \DB::select(
            "SELECT TERMS_ID, TERMS FROM PURCHASE_TERMS WHERE IS_STANDARD = 'Y'"
        );
 
        // Remove any existing standard terms for this PO
        $standardIds = array_column($standardTerms, 'TERMS_ID');
        if (!empty($standardIds)) {
            $placeholders = implode(',', array_fill(0, count($standardIds), '?'));
            \DB::statement(
                "DELETE FROM PUR_ORDER_TERMS WHERE PUR_ORDER_PK = ? AND TERMS_ID IN ({$placeholders})",
                array_merge([$purOrderPk], $standardIds)
            );
        }
 
        // Re-insert standard terms
        foreach ($standardTerms as $term) {
            static::create([
                'PUR_ORDER_PK' => $purOrderPk,
                'TERMS_ID'     => $term->TERMS_ID,
                'TERMES'       => $term->TERMS,
            ]);
        }
    }
}
