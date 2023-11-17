<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAS_PO_PurchaseOrderDetail extends Model
{
    protected $connection = 'mars';
    protected $table      = 'PO_PurchaseOrderDetail';
    protected $casts      = [
        'LineKey' => 'integer',
        'ItemCode' => 'string',
        'WarehouseCode' => 'string',
        'QuantityOrdered' => 'integer',
        'QuantityReceived' => 'integer',
        'QuantityBackordered' => 'integer',
        'UnitCost' => 'double',
    ];

    public static function getChinaLineDetail($purchase_order, $productCode)
    {
        return self::select('WarehouseCode', 'QuantityOrdered', 'QuantityReceived', 'QuantityBackordered', 'UnitCost')
            ->where('PurchaseOrderNo', str_pad($purchase_order, 7, '0', STR_PAD_LEFT))
            ->where('ItemCode', $productCode)
            ->first();
    }

    public function getChinaPurchaseOrderDetail($purchase_order, $product_code)
    {
        return self::select('WarehouseCode', 'PurchaseOrderNo', 'LineKey', 'ItemCode')
            ->where('PurchaseOrderNo', str_pad($purchase_order, 7, '0', STR_PAD_LEFT))
            ->where('ItemCode', $product_code)
            ->first();
    }

    public function checkChinaLine($productCode)
    {
        return self::select('ItemCode')
            ->where('ItemCode', $productCode)
            ->orWhere('ItemCode', $productCode . '-G')
            ->groupBy('ItemCode')
            ->get();
    }

    public function getQtyOpen($po, $pol)
    {
        return self::selectRaw('(QuantityOrdered-QuantityReceived) as qtyOpen')
            ->where('PurchaseOrderNo', str_pad($po, 7, "0", STR_PAD_LEFT))
            ->where('LineKey', str_pad(round($pol, 0), 6, "0", STR_PAD_LEFT))
            ->first();
    }

    public function getPOL($poNum)
    {
        return self::select('LineKey', 'ItemCode')
            ->where('PurchaseOrderNo', $poNum)
            ->where('ExtensionAmt', '!=', '0.00')
            ->orderBy('LineSeqNo')
            ->get();
    }

    public function getPOLMultiple($poNum, $productCode)
    {
        return self::select('LineKey', 'ItemCode')
            ->where('PurchaseOrderNo', $poNum)
            ->where('ItemCode', $productCode)
            ->orderBy('LineSeqNo')
            ->first();
    }

    public function getNonZeroLines($poNum)
    {
        return self::select('LineKey', 'ItemCode')
            ->where('PurchaseOrderNo', $poNum)
            ->where('OriginalUnitCost', '>', '0')
            ->orderBy('LineSeqNo')
            ->get();
    }

    public function getLineDetail($po, $pol)
    {
        return self::select('QuantityOrdered', 'QuantityReceived')
            ->where('PurchaseOrderNo', str_pad($po, 7, '0', STR_PAD_LEFT))
            ->where('LineKey', str_pad($pol, 6, '0', STR_PAD_LEFT))
            ->first();
    }

    public function getOpenPODetail()
    {
        return self::join('PO_PurchaseOrderHeader', 'PO_PurchaseOrderHeader.PurchaseOrderNo', '=', 'PO_PurchaseOrderDetail.PurchaseOrderNo')
            ->select('PO_PurchaseOrderDetail.PurchaseOrderNo', 'PO_PurchaseOrderDetail.LineKey', 'PO_PurchaseOrderDetail.ItemCode', 'PO_PurchaseOrderDetail.QuantityOrdered', 'PO_PurchaseOrderDetail.QuantityReceived')
            ->where('VendorNo', 'CISCO')
            ->where(function ($query) {
                $query->where('OrderStatus', 'B')
                    ->orWhere('OrderStatus', 'O');
            })
            ->where('ItemCode', 'not like', '/%')
            ->get();
    }

    public static function getBuildsOpen($productCode)
    {
        return self::select('PurchaseOrderNo')
            ->selectRaw('SUM(QuantityOrdered) as QtyOrdered, SUM(QuantityReceived) as QtyReceived, SUM(QuantityBackordered) as QtyBackordered')
            ->where('ItemCode', $productCode)
            ->groupBy('PurchaseOrderNo')
            ->groupBy('ItemCode')
            ->havingRaw('SUM(QuantityOrdered) - SUM(QuantityReceived) > 0')
            ->get();

    }

    public static function getLineWarehouse($purchase_order, $productCode)
    {
        return self::select('WarehouseCode')
            ->where('PurchaseOrderNo', '=', $purchase_order)
            ->where('ItemCode', '=', $productCode)
            ->first();
    }

    public static function getLineWarehouseByPol($purchase_order, $po_line)
    {
        return self::select('WarehouseCode')
            ->where('PurchaseOrderNo', '=', $purchase_order)
            ->where('LineKey', '=', str_pad($po_line, 6, '0', STR_PAD_LEFT))
            ->first()->WarehouseCode;
    }

    public static function getPolWarehouse($purchase_order, $productCode)
    {
        return self::select('WarehouseCode', 'LineKey')
            ->where('PurchaseOrderNo', '=', $purchase_order)
            ->where('ItemCode', '=', $productCode)
            ->whereRaw('QuantityReceived < QuantityOrdered')
            ->first();
    }
}
