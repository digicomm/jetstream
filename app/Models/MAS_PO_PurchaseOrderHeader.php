<?php

namespace App\Models;

use App\Models\MAS\PO_PurchaseOrderDetail;
use Illuminate\Database\Eloquent\Model;

class MAS_PO_PurchaseOrderHeader extends Model
{
    protected $connection   = 'mars';
    protected $table        = 'PO_PurchaseOrderHeader';
    protected $primaryKey   = 'PurchaseOrderNo';
    public    $incrementing = false;
    protected $keyType      = 'string';

    public function getPOWhs($poNo)
    {
        return self::select('WarehouseCode')
            ->where('PurchaseOrderNo', $poNo)
            ->first();
    }

    public function getClosedPO()
    {
        return self::select('PurchaseOrderNo')
            ->where('VendorNo', 'CISCO')
            ->where('OrderStatus', 'X')
            ->get();
    }

    public function getLines()
    {
        return $this->hasMany(PO_PurchaseOrderDetail::class, 'PurchaseOrderNo', 'PurchaseOrderNo')
            ->select('PurchaseOrderNo', 'LineKey', 'ItemCode', 'WarehouseCode', 'QuantityOrdered', 'QuantityReceived');
    }

    public function getOpenPurchaseOrders()
    {
        return self::select('PurchaseOrderNo', 'VendorNo', 'PurchaseName', 'OrderStatus')
            ->where('OrderStatus', '<>', 'X')
            ->whereRaw('CAST(PurchaseOrderNo AS int) > 9899')
            ->orderByDesc('PurchaseOrderNo')
            ->get();
    }

    public function getPOLines()
    {
        return $this->hasMany(SP_PurchaseOrders::class, 'purchase_order', 'PurchaseOrderNo')
            ->select('po_line', 'product_code', 'quantity_ordered', 'quantity_received', 'quantity_arn', 'quantity_open', 'warehouse_code')
            ->orderBy('po_line');
    }
}
