<?php

namespace App\Models;

use ErrorException;
use Illuminate\Database\Eloquent\Model;

class SO_SalesOrderDetail extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'SO_SalesOrderDetail';

    public static function getVsWMS()
    {
        return self::select('SO_SalesOrderHeader.SalesOrderNo', 'SO_SalesOrderHeader.OrderDate', 'SO_SalesOrderHeader.OrderStatus', 'SP_OpenOrders.order_number', 'SO_SalesOrderHeader.ShipExpireDate', 'SO_SalesOrderHeader.CustomerNo', 'SO_SalesOrderHeader.ShipToCode', 'SO_SalesOrderDetail.WarehouseCode', 'SO_SalesOrderDetail.UDF_PO_LINE', 'SO_SalesOrderDetail.ItemCode', 'SO_InvoiceHeader.InvoiceNo')
            ->selectRaw('(SO_SalesOrderDetail.QuantityOrdered-SO_SalesOrderDetail.QuantityShipped) as QuantityOpen')
            ->leftJoin('SO_InvoiceHeader', 'SO_SalesOrderDetail.SalesOrderNo', '=', 'SO_InvoiceHeader.SalesOrderNo')
            ->leftJoin('SO_SalesOrderHeader', 'SO_SalesOrderHeader.SalesOrderNo', '=', 'SO_SalesOrderDetail.SalesOrderNo')
            ->leftJoin('SP_OpenOrders', function ($join) {
                $join->on('SP_OpenOrders.sage_sales_order', '=', 'SO_SalesOrderDetail.SalesOrderNo')
                    ->on('SP_OpenOrders.po_line', '=', 'SO_SalesOrderDetail.UDF_PO_LINE');
            })
            ->where('SO_SalesOrderHeader.OrderType', '<>', 'M')
            ->where('SO_SalesOrderDetail.ItemCode', 'not like', '/%')
            ->where('SO_SalesOrderHeader.OrderStatus', 'not like', 'H')
            ->where('order_number', NULL)->where('InvoiceNo', NULL)
            ->whereRaw('(SO_SalesOrderDetail.QuantityOrdered-SO_SalesOrderDetail.QuantityShipped) > 0')
            ->orderby('SO_SalesOrderDetail.SalesOrderNo')
            ->orderBy('SO_SalesOrderDetail.UDF_PO_LINE')
            ->get();
    }

    public function getPromiseDate($salesOrder, $pol)
    {
        try {
            return self::select('PromiseDate')->where('SalesOrderNo', str_pad($salesOrder, "7", "0", STR_PAD_LEFT))->where('UDF_PO_LINE', round($pol, 2))->first()->PromiseDate;
        } catch (ErrorException $e) {
            return $salesOrder . "-" . $pol;
        }
    }

    public function getMoveToALC()
    {
        return self::select('SO_SalesOrderHeader.SalesOrderNo', 'SO_SalesOrderHeader.OrderType', 'SO_SalesOrderDetail.UDF_PO_LINE', 'CI_Item.ProductLine')
            ->join('SO_SalesOrderHeader', 'SO_SalesOrderDetail.SalesOrderNo', '=', 'SO_SalesOrderHeader.SalesOrderNo')
            ->join('CI_Item', 'SO_SalesOrderDetail.ItemCode', '=', 'CI_Item.ItemCode')
            ->where('SO_SalesOrderHeader.OrderType', '<>', 'M')
            ->orderBy('SO_SalesOrderDetail.SalesOrderNo')
            ->orderBy('SO_SalesOrderDetail.UDF_PO_LINE')
            ->get();
    }


}
