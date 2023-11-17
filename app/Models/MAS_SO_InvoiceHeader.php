<?php

namespace App\Models;

use ErrorException;
use Illuminate\Database\Eloquent\Model;

class MAS_SO_InvoiceHeader extends Model
{
    protected $connection = 'mars';
    protected $table      = 'SO_InvoiceHeader';
    protected $casts      = [
        'InvoiceTotal' => 'integer',
        'Top5Total' => 'double',
        'Top5Quantity' => 'integer'
    ];

    public static function getAutoCount()
    {
        return self::select('InvoiceNo')->where('UDF_WMS_NO', '<>', '')->count();
    }

    public static function getManualCount()
    {
        return self::select('InvoiceNo')->where('UDF_WMS_NO', '')->count();
    }

    public static function checkForInvoice($sales_order, $order_number)
    {
        return self::select('SalesOrderNo')
            ->where('SalesOrderNo', str_pad($sales_order, "7", "0", STR_PAD_LEFT))
            ->where('UDF_WMS_NO', 'like', $order_number . '-__')
            ->count();

    }

    public static function getInvoiceNumber($sales_order)
    {
        return self::select('InvoiceNo')
            ->where('SalesOrderNo', str_pad($sales_order, "7", "0", STR_PAD_LEFT))
            ->first();
    }

    public static function checkInvoiceDetail($salesOrder, $pol, $itemCode)
    {
        return self::select('SO_InvoiceHeader.SalesOrderNo', 'SO_InvoiceDetail.InvoiceNo', 'SO_InvoiceDetail.UDF_PO_LINE', 'SO_InvoiceDetail.ItemCode', 'SO_InvoiceDetail.WarehouseCode', 'SO_InvoiceDetail.ItemType')
            ->selectRaw('CAST(SO_InvoiceDetail.QuantityShipped AS int) as QuantityShipped')
            ->where('SO_InvoiceHeader.SalesOrderNo', str_pad($salesOrder, 7, "0", STR_PAD_LEFT))
            ->where(function ($query) use ($pol, $itemCode) {
                $query->where('UDF_PO_LINE', round($pol, 2))->orWhere('UDF_PO_LINE', str_pad(round($pol, 2), 5, "0", STR_PAD_LEFT));
            })->where('SO_InvoiceDetail.ItemCode', $itemCode)
            ->leftJoin('SO_InvoiceDetail', 'SO_InvoiceHeader.InvoiceNo', '=', 'SO_InvoiceDetail.InvoiceNo')
            ->first();
    }

    public static function getWarehouseInvoiceDetail()
    {
        return self::select('SO_InvoiceHeader.SalesOrderNo', 'SO_InvoiceDetail.InvoiceNo', 'SO_InvoiceDetail.UDF_PO_LINE', 'SO_InvoiceDetail.ItemCode', 'SO_InvoiceDetail.WarehouseCode', 'SO_InvoiceDetail.QuantityShipped', 'SO_InvoiceDetail.ItemType')
            ->leftJoin('SO_InvoiceDetail', 'SO_InvoiceHeader.InvoiceNo', '=', 'SO_InvoiceDetail.InvoiceNo')
            ->get();
    }

    public static function getInvoiceTotal()
    {
        return self::selectRaw('SUM(TaxableAmt) + SUM(NonTaxableAmt) + SUM(SalesTaxAmt) + SUM(FreightAmt) as invoiceTotal')
            ->where('InvoiceType', '=', 'IN')
            ->first()
            ->invoiceTotal;
    }


    public static function getPostedInvoices()
    {
        return self::select('SalesOrderNo')->get();
    }

    public static function getInvoiceNo($salesOrder)
    {
        try {
            return self::select('InvoiceNo')->where('SalesOrderNo', str_pad($salesOrder, "7", "0", STR_PAD_LEFT))->first()->InvoiceNo;
        } catch (ErrorException $e) {
            return 0;
        }
    }

    public static function getTop3()
    {
        return self::leftJoin('SO_InvoiceDetail', 'SO_InvoiceHeader.InvoiceNo', '=', 'SO_InvoiceDetail.InvoiceNo')
            ->select('SO_InvoiceDetail.ItemCode')
            ->selectRaw('SUM(SO_InvoiceDetail.QuantityShipped) AS Quantity,SUM(SO_InvoiceDetail.ExtensionAmt) AS Total')
            ->where('SO_InvoiceHeader.InvoiceType', '=', 'IN')
            ->where('SO_InvoiceDetail.QuantityShipped', '>', '0')
            ->groupBy('SO_InvoiceDetail.ItemCode')
            ->orderByDesc('Total')
            ->limit(3)
            ->get();
    }

    public static function getNumberOrders()
    {
        return self::select('InvoiceNo')
            ->where('InvoiceType', '=', 'IN')
            ->count();
    }

    public static function getCreditMemoTotal()
    {
        return self::selectRaw('SUM(TaxableAmt) + SUM(NonTaxableAmt) + SUM(SalesTaxAmt) + SUM(FreightAmt) as invoiceTotal')
            ->where('InvoiceType', '=', 'CM')
            ->first()
            ->invoiceTotal;
    }

    public static function getNumberLines()
    {
        return self::leftJoin('SO_InvoiceDetail', 'SO_InvoiceHeader.InvoiceNo', '=', 'SO_InvoiceDetail.InvoiceNo')
            ->select('SO_InvoiceDetail.ItemCode')
            ->where('SO_InvoiceHeader.InvoiceType', '=', 'IN')
            ->where('SO_InvoiceDetail.QuantityShipped', '>', '0')
            ->count();
    }

    public function getInvoiceList()
    {
        return self::select('SalesOrderNo')->get();
    }

    public function getInvoiceQuantity()
    {
        return self::select('InvoiceNo')
            ->where('InvoiceType', '=', 'IN')
            ->count();
    }

    public function getCreditMemoQuantity()
    {
        return self::select('InvoiceNo')
            ->where('InvoiceType', '=', 'CM')
            ->count();
    }

    public static function getEndOfDayDashboardInfo(): array
    {
        $sphere_count = self::where('UDF_WMS_NO', '<>', '')
            ->where('InvoiceType', '=', 'IN')
            ->count('InvoiceNo');
        $manual_count = self::where('UDF_WMS_NO', '=', '')
            ->where('InvoiceType', '=', 'IN')
            ->count('InvoiceNo');
        $cm_count = self::where('InvoiceType', '=', 'CM')
            ->count('InvoiceNo');
        $total_count = $sphere_count + $manual_count + $cm_count;

        $sphere_invoice_total = self::selectRaw('SUM(TaxableAmt) + SUM(NonTaxableAmt) + SUM(SalesTaxAmt) + SUM(FreightAmt) as InvoiceTotal')
            ->where('InvoiceType', '=', 'IN')
            ->where('UDF_WMS_NO', '<>', '')
            ->first()
            ->InvoiceTotal;
        $manual_invoice_total = self::selectRaw('SUM(TaxableAmt) + SUM(NonTaxableAmt) + SUM(SalesTaxAmt) + SUM(FreightAmt) as InvoiceTotal')
            ->where('InvoiceType', '=', 'IN')
            ->where('UDF_WMS_NO', '=', '')
            ->first()
            ->InvoiceTotal;
        $cm_total = self::selectRaw('SUM(TaxableAmt) + SUM(NonTaxableAmt) + SUM(SalesTaxAmt) + SUM(FreightAmt) as InvoiceTotal')
            ->where('InvoiceType', '=', 'CM')
            ->first()
            ->InvoiceTotal;
        $invoice_total = $sphere_invoice_total + $manual_invoice_total + $cm_total;

        $top5 = self::leftJoin('SO_InvoiceDetail', 'SO_InvoiceHeader.InvoiceNo', '=', 'SO_InvoiceDetail.InvoiceNo')
            ->select('SO_InvoiceDetail.ItemCode')
            ->selectRaw('SUM(SO_InvoiceDetail.QuantityShipped) AS Top5Quantity, SUM(SO_InvoiceDetail.ExtensionAmt) AS Top5Total')
            ->where('SO_InvoiceHeader.InvoiceType', '=', 'IN')
            ->where('SO_InvoiceDetail.QuantityShipped', '>', '0')
            ->groupBy('SO_InvoiceDetail.ItemCode')
            ->orderByDesc('Top5Total')
            ->limit(5)
            ->get();

        $sphere_lines = self::leftJoin('SO_InvoiceDetail', 'SO_InvoiceHeader.InvoiceNo', '=', 'SO_InvoiceDetail.InvoiceNo')
            ->where('SO_InvoiceHeader.UDF_WMS_NO', '<>', '')
            ->where('SO_InvoiceHeader.InvoiceType', '=', 'IN')
            ->count('SO_InvoiceDetail.InvoiceNo');
        $manual_lines = self::leftJoin('SO_InvoiceDetail', 'SO_InvoiceHeader.InvoiceNo', '=', 'SO_InvoiceDetail.InvoiceNo')
            ->where('SO_InvoiceHeader.UDF_WMS_NO', '=', '')
            ->where('SO_InvoiceHeader.InvoiceType', '=', 'IN')
            ->count('SO_InvoiceDetail.InvoiceNo');
        $cm_lines = self::leftJoin('SO_InvoiceDetail', 'SO_InvoiceHeader.InvoiceNo', '=', 'SO_InvoiceDetail.InvoiceNo')
            ->where('SO_InvoiceHeader.InvoiceType', '=', 'CM')
            ->count('SO_InvoiceDetail.InvoiceNo');
        $total_lines = $sphere_lines + $manual_lines + $cm_lines;


        if (!count($top5)) $top5[] = ['ItemCode' => ' ', 'Top5Quantity' => '', 'Top5Total' => ''];


        return array('sphere_lines' => $sphere_lines, 'manual_lines' => $manual_lines, 'cm_lines' => $cm_lines, 'total_lines' => $total_lines, 'sphere_count' => $sphere_count, 'manual_count' => $manual_count, 'cm_count' => $cm_count, 'total_count' => $total_count, 'sphere_invoice_total' => floatval($sphere_invoice_total), 'manual_invoice_total' => floatval($manual_invoice_total), 'cm_total' => floatval($cm_total), 'invoice_total' => floatval($invoice_total), 'top5' => $top5);
    }
}
