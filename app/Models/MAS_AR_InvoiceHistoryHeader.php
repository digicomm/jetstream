<?php

namespace App\Models;

use App\Models\MAS\AR_InvoiceHistoryDetail;
use App\Models\MAS\AR_InvoiceHistoryHeader;
use Illuminate\Database\Eloquent\Model;

class MAS_AR_InvoiceHistoryHeader extends Model
{
    protected $connection   = 'mars';
    protected $table        = 'AR_InvoiceHistoryHeader';
    protected $primaryKey   = 'InvoiceNo';
    protected $keyType      = 'string';
    public    $incrementing = false;

    public static function getInvoiceHeader($entry_number, $transaction_type)
    {
        switch ($transaction_type) {
            case 'CM':
            case 'SO':
                $invoice_number = substr($entry_number, 0, 7);
                break;
            default:
                $invoice_number = null;
        }
        return self::select('SalesOrderNo', 'UDF_WMS_NO', 'CustomerPONo')->where('InvoiceNo', '=', $invoice_number)->first();
    }

    public static function getShippedInvoices($startDate, $endDate)
    {
        return self::selectRaw('COUNT(InvoiceNo) as invoices')
            ->where('InvoiceDate', '>=', $startDate)
            ->where('InvoiceDate', '<=', $endDate)
            ->first()->invoices;
    }

    public static function getShippedShipments($startDate, $endDate)
    {
        return count(self::select('InvoiceDate', 'CustomerNo', 'ShipToCode')
            ->where('InvoiceDate', '>=', $startDate)
            ->where('InvoiceDate', '<=', $endDate)
            ->groupBy('CustomerNo', 'ShipToCode', 'InvoiceDate')
            ->get());
    }

    public static function getShippedNumbers($startDate, $endDate)
    {
        return self::selectRaw('SUM(TaxableSalesAmt) as taxable, SUM(NonTaxableSalesAmt) as nonTaxable, SUM(FreightAmt) as freight, SUM(SalesTaxAmt) as salesTax')
            ->where('AR_InvoiceHistoryHeader.InvoiceDate', '>=', $startDate)
            ->where('AR_InvoiceHistoryHeader.InvoiceDate', '<=', $endDate)
            ->first();
    }

    public static function getLinesSummary($date)
    {
        return self::select('AR_InvoiceHistoryHeader.InvoiceNo', 'AR_InvoiceHistoryHeader.TaxableSalesAmt', 'AR_InvoiceHistoryHeader.NonTaxableSalesAmt', 'AR_InvoiceHistoryHeader.FreightAmt')
            ->selectRaw('FORMAT(AR_InvoiceHistoryHeader.OrderDate, \'MM/dd/yy\') as InvoiceDate, FORMAT(AR_InvoiceHistoryHeader.OrderDate, \'MM/dd/yy\') as OrderDate, COUNT(DetailSeqNo) as LinesShipped, (AR_InvoiceHistoryHeader.TaxableSalesAmt + AR_InvoiceHistoryHeader.NonTaxableSalesAmt + AR_InvoiceHistoryHeader.FreightAmt) as InvTotal')
            ->where('AR_InvoiceHistoryHeader.InvoiceDate', $date)
            ->where('AR_InvoiceHistoryDetail.QuantityShipped', '<>', '0')
            ->where('AR_InvoiceHistoryDetail.ItemCode', 'not like', '/%')
            ->join('AR_InvoiceHistoryDetail', 'AR_InvoiceHistoryHeader.InvoiceNo', '=', 'AR_InvoiceHistoryDetail.InvoiceNo')
            ->groupBy('AR_InvoiceHistoryHeader.InvoiceNo', 'AR_InvoiceHistoryHeader.InvoiceDate', 'AR_InvoiceHistoryHeader.OrderDate', 'AR_InvoiceHistoryHeader.TaxableSalesAmt', 'AR_InvoiceHistoryHeader.NonTaxableSalesAmt', 'AR_InvoiceHistoryHeader.FreightAmt')
            ->get();
    }

    public static function getLinesDetail($date)
    {
        return self::select('AR_InvoiceHistoryHeader.InvoiceNo', 'AR_InvoiceHistoryHeader.InvoiceDate', 'AR_InvoiceHistoryHeader.OrderDate', 'AR_InvoiceHistoryHeader.TaxableSalesAmt', 'AR_InvoiceHistoryHeader.NonTaxableSalesAmt', 'AR_InvoiceHistoryHeader.FreightAmt', 'AR_InvoiceHistoryDetail.ItemCode', 'AR_InvoiceHistoryDetail.ItemCodeDesc', 'AR_InvoiceHistoryDetail.ProductLine', 'AR_InvoiceHistoryDetail.QuantityShipped')
            ->selectRaw('FORMAT(AR_InvoiceHistoryHeader.OrderDate, \'MM/dd/yy\') as InvoiceDate, FORMAT(AR_InvoiceHistoryHeader.OrderDate, \'MM/dd/yy\') as OrderDate, (AR_InvoiceHistoryHeader.TaxableSalesAmt + AR_InvoiceHistoryHeader.NonTaxableSalesAmt + AR_InvoiceHistoryHeader.FreightAmt) as InvTotal')
            ->where('AR_InvoiceHistoryHeader.InvoiceDate', $date)
            ->where('AR_InvoiceHistoryDetail.QuantityShipped', '<>', '0')
            ->where('AR_InvoiceHistoryDetail.ItemCode', 'not like', '/%')
            ->join('AR_InvoiceHistoryDetail', 'AR_InvoiceHistoryHeader.InvoiceNo', '=', 'AR_InvoiceHistoryDetail.InvoiceNo')
            ->groupBy('AR_InvoiceHistoryHeader.InvoiceNo', 'AR_InvoiceHistoryHeader.InvoiceDate', 'AR_InvoiceHistoryHeader.OrderDate', 'AR_InvoiceHistoryHeader.TaxableSalesAmt', 'AR_InvoiceHistoryHeader.NonTaxableSalesAmt', 'AR_InvoiceHistoryHeader.FreightAmt', 'AR_InvoiceHistoryDetail.ItemCode', 'AR_InvoiceHistoryDetail.UDF_PO_LINE', 'AR_InvoiceHistoryDetail.ItemCodeDesc', 'AR_InvoiceHistoryDetail.ProductLine', 'AR_InvoiceHistoryDetail.QuantityShipped')
            ->get();
    }

    public function getOrderDetails()
    {
        return $this->hasMany(AR_InvoiceHistoryDetail::class, 'InvoiceNo', 'InvoiceNo')->select('ItemCode')->where('ItemCode', '!=', '/C');
    }
}
