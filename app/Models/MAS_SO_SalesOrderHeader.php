<?php

namespace App\Models;

use App\Models\MAS\SO_SalesOrderDetail;
use Illuminate\Database\Eloquent\Model;

class MAS_SO_SalesOrderHeader extends Model
{
    public    $incrementing = false;
    protected $connection   = 'mars';
    protected $table        = 'SO_SalesOrderHeader';
    protected $primaryKey   = 'SalesOrderNo';
    protected $keyType      = 'string';

    public static function getBuilds()
    {
        return self::select('SO_SalesOrderHeader.SalesOrderNo', 'SO_SalesOrderHeader.CustomerNo', 'SO_SalesOrderHeader.ShipToCity', 'SO_SalesOrderDetail.PromiseDate', 'SO_SalesOrderDetail.UDF_PO_LINE', 'SO_SalesOrderDetail.ItemCode', 'SO_SalesOrderDetail.QuantityOrdered', 'SO_SalesOrderDetail.QuantityShipped')->selectRaw('(SO_SalesOrderDetail.QuantityOrdered - SO_SalesOrderDetail.QuantityShipped) as QuantityOpen')->join('SO_SalesOrderDetail', 'SO_SalesOrderHeader.SalesOrderNo', '=', 'SO_SalesOrderDetail.SalesOrderNo')->whereRaw('(SO_SalesOrderDetail.QuantityOrdered-SO_SalesOrderDetail.QuantityShipped) > 0')->where('SO_SalesOrderDetail.UDF_BUILD', 'T')->where('SO_SalesOrderHeader.OrderStatus', 'O')->where('SO_SalesOrderHeader.OrderType', '!=', 'M')->where(function ($query) {
            $query->where('SO_SalesOrderDetail.ItemCode', 'not like', '1___G___________')->where('SO_SalesOrderDetail.ItemCode', 'not like', 'GAGMSAD%')->where('SO_SalesOrderDetail.ItemCode', 'not like', 'GAGMSAT%')->where('SO_SalesOrderDetail.ItemCode', 'not like', 'GAGMLES%');
        })->get();
    }

    public static function getHeader($salesOrder)
    {
        return self::where('SalesOrderNo', str_pad($salesOrder, "7", "0", STR_PAD_LEFT))->first();
    }

    public static function getGainmakersByCustomer()
    {
        $gainmakers = collect(self::select('SO_SalesOrderHeader.SalesOrderNo', 'SO_SalesOrderHeader.CustomerNo', 'SO_SalesOrderHeader.ShipToCity', 'SO_SalesOrderDetail.PromiseDate', 'SO_SalesOrderDetail.UDF_PO_LINE', 'SO_SalesOrderDetail.ItemCode', 'SO_SalesOrderDetail.QuantityOrdered', 'SO_SalesOrderDetail.QuantityShipped')->selectRaw('(SO_SalesOrderDetail.QuantityOrdered-SO_SalesOrderDetail.QuantityShipped) as QuantityOpen, LEFT(SO_SalesOrderDetail.ItemCode,6) as Family')->join('SO_SalesOrderDetail', 'SO_SalesOrderHeader.SalesOrderNo', '=', 'SO_SalesOrderDetail.SalesOrderNo')->where('SO_SalesOrderHeader.OrderType', '!=', 'M')->where('SO_SalesOrderDetail.ItemCode', 'like', '1___G___________')->get());
        $legainmakers12 = collect(self::select('SO_SalesOrderHeader.SalesOrderNo', 'SO_SalesOrderHeader.CustomerNo', 'SO_SalesOrderHeader.ShipToCity', 'SO_SalesOrderDetail.PromiseDate', 'SO_SalesOrderDetail.UDF_PO_LINE', 'SO_SalesOrderDetail.ItemCode', 'SO_SalesOrderDetail.QuantityOrdered', 'SO_SalesOrderDetail.QuantityShipped')->selectRaw('(SO_SalesOrderDetail.QuantityOrdered-SO_SalesOrderDetail.QuantityShipped) as QuantityOpen, LEFT(SO_SalesOrderDetail.ItemCode,6) as Family')->join('SO_SalesOrderDetail', 'SO_SalesOrderHeader.SalesOrderNo', '=', 'SO_SalesOrderDetail.SalesOrderNo')->where('SO_SalesOrderHeader.OrderType', '!=', 'M')->where(function ($query) {
            $query->where('SO_SalesOrderDetail.ItemCode', 'like', 'GMLES%');
        })->get());
        $sagainmakers12 = collect(self::select('SO_SalesOrderHeader.SalesOrderNo', 'SO_SalesOrderHeader.CustomerNo', 'SO_SalesOrderHeader.ShipToCity', 'SO_SalesOrderDetail.PromiseDate', 'SO_SalesOrderDetail.UDF_PO_LINE', 'SO_SalesOrderDetail.ItemCode', 'SO_SalesOrderDetail.QuantityOrdered', 'SO_SalesOrderDetail.QuantityShipped')->selectRaw('(SO_SalesOrderDetail.QuantityOrdered-SO_SalesOrderDetail.QuantityShipped) as QuantityOpen, LEFT(SO_SalesOrderDetail.ItemCode,7) as Family')->join('SO_SalesOrderDetail', 'SO_SalesOrderHeader.SalesOrderNo', '=', 'SO_SalesOrderDetail.SalesOrderNo')->where('SO_SalesOrderHeader.OrderType', '!=', 'M')->where(function ($query) {
            $query->where('SO_SalesOrderDetail.ItemCode', 'like', 'GMSAD%')->orWhere('SO_SalesOrderDetail.ItemCode', 'like', 'GMSAT%');

        })->get());
        $merged = $gainmakers->merge($legainmakers12);
        $merged = $merged->merge($sagainmakers12);
        return $merged;
    }

    public static function getVMI()
    {
        return self::select('SO_SalesOrderHeader.OrderDate', 'SO_SalesOrderHeader.SalesOrderNo', 'SO_SalesOrderHeader.CustomerNo', 'SO_SalesOrderHeader.ShipToCity', 'SO_SalesOrderDetail.ItemCode', 'SO_SalesOrderDetail.WarehouseCode', 'SO_SalesOrderDetail.UDF_PO_LINE', 'SO_SalesOrderDetail.PromiseDate', 'SO_SalesOrderDetail.MasterQtyBalance')->leftJoin('SO_SalesOrderDetail', 'SO_SalesOrderHeader.SalesOrderNo', '=', 'SO_SalesOrderDetail.SalesOrderNo')->where('SO_SalesOrderHeader.OrderType', '=', 'M')->where('SO_SalesOrderHeader.OrderStatus', '=', 'O')->where('SO_SalesOrderDetail.MasterQtyBalance', '>', '0')->orderBy('SO_SalesOrderHeader.OrderDate')->orderBy('SO_SalesOrderHeader.SalesOrderNo')->get();
    }

    public static function getForImport()
    {
        return self::select('SalesOrderNo', 'OrderType', 'OrderStatus', 'CustomerNo', 'ShipToCode', 'ShipToCity')
            ->selectRaw('FORMAT(OrderDate, \'yyyy-MM-dd\') as OrderDate, FORMAT(ShipExpireDate,\'yyyy-MM-dd\') as ShipExpireDate')
            ->get();
    }

    public static function getExcessSupplySO($purchase_order)
    {
        return self::select('SalesOrderNo')
            ->where('CustomerPONo', '=', $purchase_order)
            ->first();
    }

    public static function validateSageSalesOrder($sage_sales_order)
    {
        if ($sage_sales_order) {
            $result = self::select('SalesOrderNo')
                ->where('SalesOrderNo', '=', str_pad($sage_sales_order, 7, "0", STR_PAD_LEFT))
                ->exists();

            if ($result) {
                return self::select('CustomerNo')
                    ->where('SalesOrderNo', '=', str_pad($sage_sales_order, 7, "0", STR_PAD_LEFT))
                    ->first()
                    ->CustomerNo;
            }
        } else {
            return true;
        }

    }

    public function details()
    {
        return $this->hasMany(SO_SalesOrderDetail::class, 'SalesOrderNo', 'SalesOrderNo');
    }

    public static function getSalesOrdersStatus()
    {
        return self::select('SalesOrderNo', 'CustomerNo')
            ->selectRaw('CASE WHEN OrderStatus = \'H\' THEN \'Hold\' ELSE \'Open\' END AS OrderStatus')
            ->where('OrderType', '!=', 'M')
            ->orderBy('SalesOrderNo')
            ->get();
    }
}

