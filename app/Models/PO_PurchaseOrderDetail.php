<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PO_PurchaseOrderDetail extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'PO_PurchaseOrderDetail';

    public static function getOpenQuantity($po, $pol)
    {
        return self::selectRaw('(QuantityOrdered-QuantityReceived) as quantity_open')
            ->where('PurchaseOrderNo', $po)
            ->where('LineKey', str_pad(round($pol, 0), 6, "0", STR_PAD_LEFT))
            ->first();

    }

    public static function checkLine($itemCode)
    {
        return self::select('PO_PurchaseOrderDetail.ItemCode')
            ->join('PO_PurchaseOrderHeader', 'PO_PurchaseOrderDetail.PurchaseOrderNo', '=', 'PO_PurchaseOrderHeader.PurchaseOrderNo')
            ->whereIn('PO_PurchaseOrderHeader.VendorNo', ['ASICOM', 'GLOTRA', 'NINELE'])
            ->where(function ($query) use ($itemCode) {
                $query->where('PO_PurchaseOrderDetail.ItemCode', '=', $itemCode)
                    ->orWhere('PO_PurchaseOrderDetail.ItemCode', '=', $itemCode . '-G');
            })
            ->groupBy('PO_PurchaseOrderDetail.ItemCode')
            ->get();
    }


    public static function getLineWarehouse($purchaseOrder, $itemCode)
    {
        return self::select('WarehouseCode')->where('PurchaseOrderNo', '=', str_pad($purchaseOrder, 7, '0', STR_PAD_LEFT))->where(function ($query) use ($itemCode) {
            $query->where('ItemCode', '=', $itemCode)->orWhere('ItemCode', '=', $itemCode . '-G')->orWhere('ItemCode', '=', $itemCode . '=-G');
        })->first();
    }
}
