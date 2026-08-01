<?php

namespace App\Services;

use App\Models\Inventory\PurOrderMaster;
use App\Models\Inventory\PurOrderStyle;
use App\Models\Inventory\PurOrderDetails;
use App\Models\Inventory\PurOrderTerms;
use DB;
/**
 * PurchaseOrderService
 *
 * Consolidates all business logic extracted from pur_order.fmb triggers.
 * Each method documents the original Oracle Forms trigger it replaces.
 */
class PurchaseOrderService
{
    // =========================================================
    // WHEN-NEW-FORM-INSTANCE (Form)
    // - Populate PAY_TERMS LOV from PAY_TRAMS
    // - Populate MEAUSER LOV from UNIT_MEASUREMENT
    // - Hide PO_CHECK canvas
    // Returns data needed to populate the initial form view
   public function saveOrder($data)
    {
        return DB::transaction(function () use ($data) {

            // =========================
            // GENERATE PK (LIKE ORACLE SEQ)
            // =========================
$master = $data['master'] ?? [];

$pk = $master['PUR_ORDER_PK'] ?? null;

if (!$pk) {
    throw new \Exception("PUR_ORDER_PK missing from UI");
}

DB::statement("
    INSERT INTO PUR_ORDER_MASTER
    (PUR_ORDER_PK, PUR_ORDER_NO, PUR_ORDER_DATE, SUPPLIER_NO, CURRENCY_RATE)
    VALUES (?, ?, ?, ?, ?)
", [
    $pk,
    $master['PUR_ORDER_NO'],
    $master['PUR_ORDER_DATE'],
    $master['SUPPLIER_NO'],
    $master['CURRENCY_RATE'] ?? 1
]);

            // =========================
            // DETAILS INSERT
            // =========================

$styles = $data['styles'] ?? [];

if (empty($styles)) {
    throw new \Exception("No styles received");
}

foreach ($styles as $style) {

    if (empty($style['PO_NUMBER_ID'])) {
        throw new \Exception("PO_NUMBER_ID missing in style");
    }

    DB::statement("
        INSERT INTO PUR_ORDER_STYLE
        (PUR_ORDER_PK, PO_NUMBER, PO_NUMBER_ID, ORDER_NO,  PO_QTY)
        VALUES (?, ?, ?, ?, ?)
    ", [
        $pk,
        $style['PO_NUMBER'] ?? '',
        $style['PO_NUMBER_ID'],
        $style['ORDER_NO'] ?? '',
        $style['PO_QTY'] ?? 0
    ]);

    foreach ($style['details'] ?? [] as $d) {

        if (empty($d['ITEM_ID'])) continue;

        DB::statement("
            INSERT INTO PUR_ORDER_DETAILS
            (PUR_ORDER_PK, PO_NUMBER_ID, ITEM_ID, QUANTITY, ITEM_RATE)
            VALUES (?, ?, ?, ?, ?)
        ", [
            $pk,
            $d['PO_NUMBER_ID'],
            $d['ITEM_ID'],
            $d['QUANTITY'] ?? 0,
            $d['ITEM_RATE'] ?? 0
        ]);
    }
}




            // =========================
            // TERMS AUTO INSERT
            // =========================
            DB::statement("
                 INSERT INTO PUR_ORDER_TERMS (PUR_ORDER_PK, TERMS_ID, TERMES)
                SELECT ?, TERMS_ID, TERMS
                FROM PURCHASE_TERMS
                WHERE IS_STANDARD = 'Y'
            ", [$pk]);

            return $pk;
        });
    }

    // =========================
    // LOV — PO
    // =========================
    public function getPoLov($q = null)
    {
         return DB::select("
        SELECT 
            OPN.PO_NUMBER,
            OPN.PO_NUMBER_ID,
            OM.ORDER_NO,
            COM.GET_PARTY_NAME(OM.BUYER_ID) AS BUYER_NAME,
            OPN.PO_QUANTITY
        FROM ORDER_PO_NUMBER OPN
        JOIN ORDER_MASTER OM 
            ON OM.ORDER_NO_ID = OPN.ORDER_NO_ID
        WHERE (:q IS NULL OR UPPER(OPN.PO_NUMBER) LIKE '%'||UPPER(:q)||'%')
        and IS_CLOSE='0'
    ", ['q' => $q]);
    }

    // =========================
    // LOV — ITEM
    // =========================
    public function getItemLov($q, $orderNo)
    {
        return DB::select("
           SELECT
  ITEM_ID,
  COM.GET_ITEM_NAME(ITEM_ID) AS ITEM_NAME
FROM
  BUYER_ACCESORIES_LIST BAL,F_STORE.BUYER_ACCESORIES_LIST_DT IM
  WHERE IM.ID_PK = BAL.ID_PK AND  (:q IS NULL OR UPPER(ITEM_ID) LIKE '%'||UPPER(:q)||'%')
        ", ['q' => $q]);
    }

    // =========================
    // LOV — SUPPLIER
    // =========================
    public function getSupplierLov($q)
    {
        return DB::select("
            SELECT PARTY_ID, PARTY_NAME
            FROM PARTY_PROFILE
            WHERE UPPER(PARTY_NAME) LIKE '%'||UPPER(:q)||'%'
        ", ['q' => $q]);
    }

    // =========================
    // WHEN-VALIDATE-ITEM
    // =========================
    public function validateItem($data)
    {
        $dup = DB::selectOne("
            SELECT COUNT(*) CNT
            FROM PUR_ORDER_DETAILS
            WHERE ITEM_ID = :item
        ", ['item' => $data['ITEM_ID']]);

        $price = DB::selectOne("
            SELECT GET_ACC_ITEM_PRICE(NULL, :supp, :item) PRICE
            FROM DUAL
        ", [
            'supp' => $data['SUPPLIER_NO'],
            'item' => $data['ITEM_ID']
        ]);

        return [
            'is_duplicate' => ($dup->cnt ?? 0) > 0,
            'item_rate' => $price->price ?? 0
        ];
    }

    // =========================
    // PO CHECK PANEL
    // =========================
    public function getPoCheckData()
    {
        return DB::select("
            SELECT PO_NUMBER,
                   ITEM_ID,
                   ITEM_NAME,
                   ITEM_QTY,
                   CURRENCY,
                   REMARK
            FROM XL_DATA_UPLOAD
        ");
    }


    // GET ORDER
public function getOrder($id)
{
    return DB::selectOne("
        SELECT * FROM PUR_ORDER_MASTER WHERE PUR_ORDER_PK = :id
    ", ['id' => $id]);
}

// UPDATE
public function updateOrder($data)
{
    DB::beginTransaction();

    try {

        $master = $data['master'];
        $styles = $data['styles'];

        // 🔁 UPDATE MASTER
        DB::update("
            UPDATE PUR_ORDER_MASTER SET
                PUR_ORDER_DATE = TO_DATE(:po_date, 'YYYY-MM-DD'),
                SUPPLIER_NO = :supplier,
                CURRENCY_RATE = :rate
            WHERE PUR_ORDER_PK = :pk
        ", [
            'po_date' => $master['PUR_ORDER_DATE'],
            'supplier' => $master['SUPPLIER_NO'],
            'rate' => $master['CURRENCY_RATE'],
            'pk' => $master['PUR_ORDER_PK']
        ]);

        // 🔥 EXISTING STYLE IDS
        $existingStyles = DB::select("
            SELECT PO_NUMBER_ID 
            FROM PUR_ORDER_STYLE 
            WHERE PUR_ORDER_PK = :pk
        ", ['pk' => $master['PUR_ORDER_PK']]);

        $existingStyleIds = collect($existingStyles)->pluck('po_number_id')->toArray();

        $incomingStyleIds = [];

        foreach ($styles as $style) {

            $incomingStyleIds[] = $style['PO_NUMBER_ID'];

            // 🔁 UPDATE OR INSERT STYLE
            if (in_array($style['PO_NUMBER_ID'], $existingStyleIds)) {

               DB::update("
    UPDATE PUR_ORDER_STYLE SET
        PO_NUMBER = :po,
        ORDER_NO = :order_no,
        PO_QTY = :qty
    WHERE PUR_ORDER_PK = :pk
    AND PO_NUMBER_ID = :poi
", [
    'po' => $style['PO_NUMBER'],
    'order_no' => $style['ORDER_NO'], // ✅ FIXED
    'qty' => $style['PO_QTY'],
    'pk' => $master['PUR_ORDER_PK'],
    'poi' => $style['PO_NUMBER_ID']
]);

            } else {

                DB::insert("
                    INSERT INTO PUR_ORDER_STYLE
                    (PUR_ORDER_PK, PO_NUMBER, PO_NUMBER_ID, ORDER_NO,  PO_QTY)
                    VALUES (:pk, :po, :poi, :order_no,  :qty)
                ", [
                    'pk' => $master['PUR_ORDER_PK'],
                    'po' => $style['PO_NUMBER'],
                    'poi' => $style['PO_NUMBER_ID'],
                    'order_no' => $style['ORDER_NO'],
                    'qty' => $style['PO_QTY']
                ]);
            }

            // 🔥 DETAILS PROCESS
            $existingDetails = DB::select("
                SELECT ITEM_ID 
                FROM PUR_ORDER_DETAILS
                WHERE PUR_ORDER_PK = :pk AND PO_NUMBER_ID = :poi
            ", [
                'pk' => $master['PUR_ORDER_PK'],
                'poi' => $style['PO_NUMBER_ID']
            ]);

            $existingItemIds = collect($existingDetails)->pluck('item_id')->toArray();
            $incomingItemIds = [];

            foreach ($style['details'] as $d) {

                $incomingItemIds[] = $d['ITEM_ID'];

                if (in_array($d['ITEM_ID'], $existingItemIds)) {

                    // 🔁 UPDATE DETAIL
                    DB::update("
                        UPDATE PUR_ORDER_DETAILS SET
                            QUANTITY = :qty,
                            ITEM_RATE = :rate
                        WHERE PUR_ORDER_PK = :pk
                        AND PO_NUMBER_ID = :poi
                        AND ITEM_ID = :item
                    ", [
                        'qty' => $d['QUANTITY'],
                        'rate' => $d['ITEM_RATE'],
                        'pk' => $master['PUR_ORDER_PK'],
                        'poi' => $style['PO_NUMBER_ID'],
                        'item' => $d['ITEM_ID']
                    ]);

                } else {

                    // ➕ INSERT DETAIL
                    DB::insert("
                        INSERT INTO PUR_ORDER_DETAILS
                        (PUR_ORDER_PK, PO_NUMBER_ID, ITEM_ID,  QUANTITY, ITEM_RATE)
                        VALUES (:pk, :poi, :item,  :qty, :rate)
                    ", [
                        'pk' => $master['PUR_ORDER_PK'],
                        'poi' => $style['PO_NUMBER_ID'],
                        'item' => $d['ITEM_ID'],
                        'qty' => $d['QUANTITY'],
                        'rate' => $d['ITEM_RATE']
                    ]);
                }
            }

            // 🔥 DELETE REMOVED DETAILS
            foreach ($existingItemIds as $oldItem) {
                if (!in_array($oldItem, $incomingItemIds)) {
                    DB::delete("
                        DELETE FROM PUR_ORDER_DETAILS
                        WHERE PUR_ORDER_PK = :pk
                        AND PO_NUMBER_ID = :poi
                        AND ITEM_ID = :item
                    ", [
                        'pk' => $master['PUR_ORDER_PK'],
                        'poi' => $style['PO_NUMBER_ID'],
                        'item' => $oldItem
                    ]);
                }
            }
        }

        // 🔥 DELETE REMOVED STYLES
        foreach ($existingStyleIds as $oldStyle) {
            if (!in_array($oldStyle, $incomingStyleIds)) {
                DB::delete("
                    DELETE FROM PUR_ORDER_STYLE
                    WHERE PUR_ORDER_PK = :pk
                    AND PO_NUMBER_ID = :poi
                ", [
                    'pk' => $master['PUR_ORDER_PK'],
                    'poi' => $oldStyle
                ]);
            }
        }

        DB::commit();

    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}

// DELETE
public function deleteOrder($id)
{
    DB::statement("DELETE FROM PUR_ORDER_MASTER WHERE PUR_ORDER_PK = ?", [$id]);
}

// RECALC
public function recalcLine($data)
{
    $val = $data['QUANTITY'] * $data['ITEM_RATE'];

    return [
        'ITEM_VALUE' => $val
    ];
}

// IMPORT
public function importFromExcel($data)
{
    return ['success' => true];
}



}