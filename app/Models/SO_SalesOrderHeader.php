<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SO_SalesOrderHeader extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'SO_SalesOrderHeader';

    public static function getSalesOrders()
    {
        return self::select('SO_SalesOrderHeader.SalesOrderNo', 'SO_SalesOrderHeader.OrderDate', 'SO_SalesOrderHeader.OrderType', 'SO_SalesOrderHeader.OrderStatus', 'SO_SalesOrderHeader.ShipExpireDate', 'SO_SalesOrderHeader.CustomerNo', 'SO_SalesOrderHeader.ShipToCode', 'SO_SalesOrderDetail.CommentText', 'SO_SalesOrderDetail.UDF_PO_LINE', 'SO_SalesOrderDetail.WarehouseCode', 'SO_SalesOrderDetail.ItemCode', 'SO_SalesOrderDetail.ItemCodeDesc', 'SO_SalesOrderDetail.UnitOfMeasure', 'SO_SalesOrderDetail.QuantityOrdered', 'SO_SalesOrderDetail.QuantityShipped', 'SO_SalesOrderDetail.QuantityBackordered')->selectRaw('SO_SalesOrderDetail.QuantityOrdered-SO_SalesOrderDetail.QuantityShipped AS OpenQty')->join('SO_SalesOrderDetail', 'SO_SalesOrderHeader.SalesOrderNo', '=', 'SO_SalesOrderDetail.SalesOrderNo')->where('SO_SalesOrderHeader.OrderType', '<>', 'M')->where('SO_SalesOrderDetail.ItemCode', 'not like', '/%')->where('SO_SalesOrderHeader.OrderStatus', 'not like', 'H')->whereRaw('SO_SalesOrderDetail.QuantityOrdered-SO_SalesOrderDetail.QuantityShipped > 0')->orderBy('SO_SalesOrderHeader.SalesOrderNo')->orderBy('SO_SalesOrderDetail.UDF_PO_LINE')->get();
    }

    public static function getHeader($salesOrder)
    {
        return self::where('SalesOrderNo', str_pad($salesOrder, "7", "0", STR_PAD_LEFT))->first();
    }

    public static function getHoldMismatches()
    {
        return self::select('SP_OpenOrders.order_number', 'SP_OpenOrders.bill_to_code')
            ->selectRaw('SO_SalesOrderHeader.SalesOrderNo as sage_sales_order')
            ->selectRaw('CASE WHEN SO_SalesOrderHeader.OrderStatus = "H" THEN "Hold" WHEN SO_SalesOrderHeader.OrderStatus = "O" THEN "Open" END AS sage_status')
            ->selectRaw('CASE WHEN SP_OpenOrders.hold_status = "Y" THEN "Hold" WHEN SP_OpenOrders.hold_status = "N" THEN "Open" END AS wms_status')
            ->leftJoin('SP_OpenOrders', 'SO_SalesOrderHeader.SalesOrderNo', '=', 'SP_OpenOrders.sage_sales_order')
            ->where('SO_SalesOrderHeader.OrderStatus', '=', 'H')
            ->where('SP_OpenOrders.hold_status', '!=', 'Y')
            ->groupBy('SO_SalesOrderHeader.SalesOrderNo', 'SP_OpenOrders.order_number', 'SP_OpenOrders.bill_to_code', 'SO_SalesOrderHeader.OrderStatus', 'SP_OpenOrders.hold_status')
            ->orderBy('SO_SalesOrderHeader.SalesOrderNo')
            ->get();
    }
}
