<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SP_OpenOrders extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'SP_OpenOrders';
    protected $casts      = [
        'hold_status' => 'boolean',
        'order_lock_status' => 'boolean',
        'printed' => 'boolean',
    ];

    /*
     * Dashboard
     */

    public static function getHoldMismatches()
    {
        return self::select('SP_OpenOrders.sage_sales_order', 'SP_OpenOrders.order_number', 'SP_OpenOrders.bill_to_code')
            ->selectRaw('CASE WHEN SO_SalesOrderHeader.OrderStatus = "H" THEN "Hold" WHEN SO_SalesOrderHeader.OrderStatus = "O" THEN "Open" END AS sage_status')
            ->selectRaw('CASE WHEN SP_OpenOrders.hold_status = 1 THEN "Hold" ELSE "Open" END AS wms_status')
            ->leftJoin('sage.SO_SalesOrderHeader', 'SP_OpenOrders.sage_sales_order', '=', 'SO_SalesOrderHeader.SalesOrderNo')
            ->where('SP_OpenOrders.hold_status', '=', 1)
            ->where('SO_SalesOrderHeader.OrderStatus', '!=', 'H')
            ->groupBy('SP_OpenOrders.sage_sales_order', 'SP_OpenOrders.order_number', 'SP_OpenOrders.bill_to_code', 'SP_OpenOrders.hold_status', 'SO_SalesOrderHeader.OrderStatus')
            ->orderBy('SP_OpenOrders.sage_sales_order')
            ->get();
    }

    public static function getHoldMismatchesCombined()
    {
        return self::select('SP_OpenOrders.sage_sales_order', 'SP_OpenOrders.order_number', 'SP_OpenOrders.bill_to_code')
            ->selectRaw('CASE WHEN SO_SalesOrderHeader.OrderStatus = "H" THEN "Hold" WHEN SO_SalesOrderHeader.OrderStatus = "O" THEN "Open" END AS sage_status')
            ->selectRaw('CASE WHEN SP_OpenOrders.hold_status = 1 THEN "Hold" ELSE "Open" END AS wms_status')
            ->leftJoin('sage.SO_SalesOrderHeader', 'SP_OpenOrders.sage_sales_order', '=', 'SO_SalesOrderHeader.SalesOrderNo')
            ->where(function (Builder $query) {
                $query->where('SP_OpenOrders.hold_status', '=', 1)
                    ->where('SO_SalesOrderHeader.OrderStatus', '!=', 'H');
            })
            ->orwhere(function (Builder $query) {
                $query->where('SP_OpenOrders.hold_status', '!=', 1)
                    ->where('SO_SalesOrderHeader.OrderStatus', '=', 'H');
            })
            ->groupBy('SP_OpenOrders.sage_sales_order', 'SP_OpenOrders.order_number', 'SP_OpenOrders.bill_to_code', 'SP_OpenOrders.hold_status', 'SO_SalesOrderHeader.OrderStatus')
            ->orderBy('SP_OpenOrders.sage_sales_order')
            ->get();
    }

    public static function getOpenOrderItems($product_code = null)
    {
        return self::select('product_code')
            ->where('product_code', 'like', $product_code ? $product_code : '%')
            ->groupBy('product_code')
            ->orderBy('product_code')
            ->get();
    }

    public static function getOrderNumber($sage_sales_order)
    {
        return self::select('order_number')->where('sage_sales_order', '=', $sage_sales_order)->first();
    }

    public static function getOrdersAllocatedByUSD()
    {
        return self::select('sage_sales_order', 'order_number', 'order_date', 'ship_to_code', 'customer_name', 'purchase_order_number', 'ship_to_city', 'hold_status', 'printed')
            ->selectRaw('FORMAT(SUM(allocated_price),2) AS allocated')
            ->where('allocated_price', '<>', '0')
            ->groupBy('sage_sales_order', 'order_number', 'order_date', 'ship_to_code', 'customer_name', 'ship_to_city', 'purchase_order_number', 'printed', 'hold_status')
            ->orderByDesc('allocated')
            ->get();
    }

    public static function getPickSheetCheck($pickSheet)
    {
        return self::where('order_number', $pickSheet)->limit(1)->first();
    }

    public static function getSageSalesOrder($pick_sheet)
    {
        return self::where('order_number', $pick_sheet)->first();
    }

    public static function getVsSage()
    {
        return self::select('SP_OpenOrders.sage_sales_order', 'SP_OpenOrders.warehouse_code', 'SP_OpenOrders.line_number', 'SP_OpenOrders.order_number', 'SP_OpenOrders.product_code', 'SP_OpenOrders.po_line', 'SP_OpenOrders.quantity_ordered', 'SP_OpenOrders.order_status', 'sage.SO_SalesOrderDetail.ItemCode', 'sage.SO_SalesOrderDetail.WarehouseCode', 'sage.SO_SalesOrderDetail.UDF_PO_LINE')
            ->selectRaw('(sage.SO_SalesOrderDetail.QuantityOrdered-sage.SO_SalesOrderDetail.QuantityShipped) as QuantityOpen')
            ->leftJoin('sage.SO_SalesOrderDetail', function ($join) {
                $join->on('SP_OpenOrders.sage_sales_order', '=', 'sage.SO_SalesOrderDetail.SalesOrderNo')
                    ->on('SP_OpenOrders.po_line', '=', 'sage.SO_SalesOrderDetail.UDF_PO_LINE');
            })
            ->where('SP_OpenOrders.po_line', '!=', '0')
            ->where('SP_OpenOrders.product_code', 'not like', '/FEE')
            ->orderby('SP_OpenOrders.sage_sales_order')
            ->orderBy('SP_OpenOrders.po_line')
            ->get();
    }

    public static function getBuildInfo($sales_order)
    {
        return self::select('order_number', 'special_instructions_2')->where('sage_sales_order', '=', $sales_order)->first();
    }

    public static function getBackorderLines($product_codes, $promise_date)
    {
        return self::select('order_date', 'sage_sales_order', 'order_number', 'product_code', 'warehouse_code', 'po_line', 'quantity_ordered', 'quantity_allocated', 'order_status', 'printed', 'special_instructions_2', 'ship_to_city', 'order_date', 'bill_to_code', 'hold_status', 'special_instructions', 'purchase_order_number', 'SO_SalesOrderDetail.PromiseDate', 'SO_SalesOrderHeader.CustomerNo')
            ->selectRaw('(quantity_ordered - quantity_allocated) as quantity_needed')
            ->leftJoin('SO_SalesOrderDetail', function ($join) {
                $join->on('SP_OpenOrders.sage_sales_order', '=', 'SO_SalesOrderDetail.SalesOrderNo')
                    ->on('SP_OpenOrders.po_line', '=', 'SO_SalesOrderDetail.UDF_PO_LINE');
            })
            ->whereIn('product_code', $product_codes)
            ->where('quantity_ordered', '>', '0')
            ->whereDate('PromiseDate', '<=', $promise_date)
            ->whereNotIn('SO_SalesOrderHeader.CustomerNo', DS_BackorderExcludeCustomer::get()->pluck('customer_no'))
            //->where('SO_SalesOrderHeader.CustomerNo','!=','CABARU')
            ->leftJoin('SO_SalesOrderHeader', 'SP_OpenOrders.sage_sales_order', '=', 'SO_SalesOrderHeader.SalesOrderNo')
            ->having('quantity_needed', '>', '0')
            ->orderBy('SO_SalesOrderDetail.PromiseDate')
            ->orderBy('order_date')
            ->orderBy('sage_sales_order')
            ->orderBy('po_line')
            ->get();
    }

    public static function getDashboardPrinted()
    {
        return self::select('order_number')
            ->where('printed', '=', true)
            ->where('hold_status', '=', false)
            ->whereIn('order_status_code', ['1', '2'])
            ->groupBy('order_number')
            ->get()
            ->count();
    }

    public static function getDashboardUnprinted()
    {
        return self::select('order_number')
            ->where('printed', '=', false)
            ->where('hold_status', '=', false)
            ->whereIn('order_status_code', ['1', '2'])
            ->groupBy('order_number')
            ->get()
            ->count();
    }

    public static function getDashboardUnallocated()
    {
        return self::select('order_number', 'order_status_code')
            ->where('order_status_code', '=', '0')
            ->where('hold_status', '=', false)
            ->orWhere('order_status_code', '=', '3')
            ->groupBy('order_number', 'order_status_code')
            ->get()
            ->count();
    }

    public static function getDashboardPartial()
    {
        return self::select('order_number', 'order_status_code')
            ->where('order_status_code', '=', '2')
            ->where('hold_status', '=', false)
            ->groupBy('order_number', 'order_status_code')
            ->get()->count();
    }

    public static function getDashboardComplete()
    {
        return self::select('order_number', 'order_status_code')
            ->where('order_status_code', '=', '1')
            ->where('hold_status', '=', false)
            ->groupBy('order_number', 'order_status_code')
            ->get()->count();
    }

    public static function getDashboardTwoWeeksPast()
    {
        return self::select('order_number')
            ->whereBetween('date_requested', array(date("Y-m-d", time() - 1209600), date("Y-m-d", time() - 86400)))
            ->where('hold_status', '=', false)
            ->groupBy('order_number')->get()->count();
    }

    public static function getDashboardTwoWeeksFuture()
    {
        return self::select('order_number')
            ->whereBetween('date_requested', array(date("Y-m-d", time()), date("Y-m-d", time() + 1209600)))
            ->where('hold_status', '=', false)
            ->groupBy('order_number')->get()->count();
    }

    public static function getDashboardPast()
    {
        return self::select('order_number')
            ->where('date_requested', '<', date("Y-m-d", time() - 1209600))
            ->where('hold_status', '=', false)
            ->groupBy('order_number')->get()->count();
    }

    public static function getDashboardFuture()
    {
        return self::select('order_number')
            ->where('date_requested', '>', date("Y-m-d", time() + 1209600))
            ->where('hold_status', '=', false)
            ->groupBy('order_number')->get()->count();
    }

    public static function getDollarsTwoWeeksPast()
    {
        return self::whereBetween('date_requested', array(date("Y-m-d", time() - 1209600), date("Y-m-d", time() - 86400)))
            ->where('hold_status', '=', false)
            ->sum('total_price');
    }

    public static function getDollarsTwoWeeksFuture()
    {
        return self::whereBetween('date_requested', array(date("Y-m-d", time()), date("Y-m-d", time() + 1209600)))
            ->where('hold_status', '=', false)
            ->sum('total_price');
    }

    public static function getDollarsPast()
    {
        return self::where('date_requested', '<', date("Y-m-d", time() - 1209600))
            ->where('hold_status', '=', false)
            ->sum('total_price');
    }

    public static function getDollarsFuture()
    {
        return self::where('date_requested', '>', date("Y-m-d", time() + 1209600))
            ->where('hold_status', '=', false)
            ->sum('total_price');
    }

    public static function getDollarsPrinted()
    {
        return self::whereIn('order_status_code', ['1', '2'])
            ->where('printed', true)
            ->where('hold_status', '=', false)
            ->sum('allocated_price');
    }

    public static function getDollarsUnprinted()
    {
        return self::whereIn('order_status_code', ['1', '2'])
            ->where('printed', false)
            ->where('hold_status', '=', false)
            ->sum('allocated_price');
    }

    public static function getOrdersStatus()
    {
        return self::select('sage_sales_order', 'order_number', 'hold_status')
            ->groupBy('sage_sales_order', 'order_number', 'hold_status')
            ->get();
    }

}
