<?php

namespace App\Models;

use App\Models\MAS\SO_SalesOrderHeader;
use Illuminate\Database\Eloquent\Model;

class MAS_SO_SalesOrderDetail extends Model
{
    protected $connection = 'mars';
    protected $table      = 'SO_SalesOrderDetail';

    public static function getDetail($salesOrder, $pol)
    {
        return self::where('SalesOrderNo', str_pad($salesOrder, "7", "0", STR_PAD_LEFT))->where('UDF_PO_LINE', round($pol, 2))->first();
    }

    public static function getMoveToALC()
    {
        return self::select('SO_SalesOrderHeader.SalesOrderNo', 'SO_SalesOrderHeader.OrderType', 'SO_SalesOrderDetail.UDF_PO_LINE', 'CI_Item.ProductLine')
            ->join('SO_SalesOrderHeader', 'SO_SalesOrderDetail.SalesOrderNo', '=', 'SO_SalesOrderHeader.SalesOrderNo')
            ->join('CI_Item', 'SO_SalesOrderDetail.ItemCode', '=', 'CI_Item.ItemCode')
            ->where('SO_SalesOrderHeader.OrderType', '<>', 'M')
            ->orderBy('SO_SalesOrderDetail.SalesOrderNo')
            ->orderBy('SO_SalesOrderDetail.UDF_PO_LINE')
            ->get();
    }

    public function header()
    {
        return $this->belongsTo(SO_SalesOrderHeader::class);
    }

    public function getOrdersPrice()
    {
        return self::select('SalesOrderNo', 'ItemCode', 'UDF_PO_LINE', 'UnitPrice')
            ->where('UDF_PO_LINE', '!=', '*')
            ->where('ItemCode', 'not like', '/%')
            ->where('UDF_PO_LINE', '!=', '')
            ->orderBy('SalesOrderNo')
            ->orderBy('UDF_PO_LINE')
            ->get();
    }

    public static function getForImport()
    {
        return self::select('SalesOrderNo', 'CommentText', 'UDF_PO_LINE', 'WarehouseCode', 'ItemCode', 'ItemCodeDesc', 'UnitOfMeasure', 'QuantityOrdered', 'QuantityShipped', 'QuantityBackordered', 'UDF_ALIAS2', 'UnitPrice')
            ->selectRaw('FORMAT(PromiseDate, \'yyyy-MM-dd\') as PromiseDate')
            ->get();
    }

    public static function validateSagePoLine($sage_sales_order, $po_line)
    {
        if ($sage_sales_order && $po_line) {
            $result = self::select('ItemCode')
                ->where('SalesOrderNo', '=', str_pad($sage_sales_order, 7, "0", STR_PAD_LEFT))
                ->where('UDF_PO_LINE', '=', $po_line);
            if ($result->exists()) {
                return $result->first()->ItemCode;
            }
        } else {
            return true;
        }

    }


}
