<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAS_PO_ReceiptHistoryHeader extends Model
{
    protected $connection   = 'mars';
    protected $table        = 'PO_ReceiptHistoryHeader';
    protected $primaryKey   = 'ReceiptNo';
    public    $incrementing = false;
    protected $casts        = [
        'ReceiptDate' => 'date'
    ];

    public static function getAllReceipts($year, $month)
    {
        $a_date = $year . "-" . $month . "-01";
        $b_date = date("Y-m-t", strtotime($a_date));
        return self::select('PO_ReceiptHistoryHeader.ReceiptType')
            ->selectRaw('COUNT(PO_ReceiptHistoryDetail.QuantityReceived) as Lines, SUM(PO_ReceiptHistoryHeader.ReceiptAmt) as Total, COUNT(DISTINCT PO_ReceiptHistoryHeader.ReceiptNo) as Count, COUNT(DISTINCT PO_ReceiptHistoryHeader.PurchaseOrderNo) as PoList')
            ->leftJoin('PO_ReceiptHistoryDetail', 'PO_ReceiptHistoryHeader.ReceiptNo', '=', 'PO_ReceiptHistoryDetail.ReceiptNo')
            ->where('PO_ReceiptHistoryHeader.ReceiptType', '!=', 'I')
            ->where('PO_ReceiptHistoryHeader.ReceiptDate', '>=', $a_date)
            ->where('PO_ReceiptHistoryHeader.ReceiptDate', '<=', $b_date)
            ->where('PO_ReceiptHistoryDetail.QuantityReceived', '!=', '0')
            ->groupBy('PO_ReceiptHistoryHeader.ReceiptType')
            ->get();
    }

    public static function getAllReceiptsTotals($year, $month)
    {
        $a_date = $year . "-" . $month . "-01";
        $b_date = date("Y-m-t", strtotime($a_date));
        return self::selectRaw('COUNT(PO_ReceiptHistoryDetail.QuantityReceived) as Lines, COUNT(DISTINCT PO_ReceiptHistoryHeader.ReceiptNo) as Count, COUNT(DISTINCT PO_ReceiptHistoryHeader.PurchaseOrderNo) as PoList')
            ->leftJoin('PO_ReceiptHistoryDetail', 'PO_ReceiptHistoryHeader.ReceiptNo', '=', 'PO_ReceiptHistoryDetail.ReceiptNo')
            ->where('PO_ReceiptHistoryHeader.ReceiptType', '!=', 'I')
            ->where('PO_ReceiptHistoryHeader.ReceiptDate', '>=', $a_date)
            ->where('PO_ReceiptHistoryHeader.ReceiptDate', '<=', $b_date)
            ->where('PO_ReceiptHistoryDetail.QuantityReceived', '!=', '0')
            ->first();
    }

    public static function getTallyReceipts($year, $month)
    {
        $a_date = $year . "-" . $month . "-01";
        $b_date = date("Y-m-t", strtotime($a_date));
        return self::select('PO_ReceiptHistoryHeader.ReceiptType')
            ->selectRaw('COUNT(PO_ReceiptHistoryDetail.QuantityReceived) as Lines, SUM(PO_ReceiptHistoryHeader.ReceiptAmt) as Total, COUNT(DISTINCT PO_ReceiptHistoryHeader.ReceiptNo) as Count, COUNT(DISTINCT PO_ReceiptHistoryHeader.PurchaseOrderNo) as PoList')
            ->leftJoin('PO_ReceiptHistoryDetail', 'PO_ReceiptHistoryHeader.ReceiptNo', '=', 'PO_ReceiptHistoryDetail.ReceiptNo')
            ->where('PO_ReceiptHistoryHeader.ReceiptType', '!=', 'I')
            ->where('PO_ReceiptHistoryHeader.ReceiptDate', '>=', $a_date)
            ->where('PO_ReceiptHistoryHeader.ReceiptDate', '<=', $b_date)
            ->where('PO_ReceiptHistoryDetail.QuantityReceived', '!=', '0')
            ->where('PO_ReceiptHistoryHeader.Comment', 'like', 'Tally%')
            ->groupBy('PO_ReceiptHistoryHeader.ReceiptType')
            ->get();
    }

    public static function getTallyReceiptsTotals($year, $month)
    {
        $a_date = $year . "-" . $month . "-01";
        $b_date = date("Y-m-t", strtotime($a_date));
        return self::selectRaw('COUNT(PO_ReceiptHistoryDetail.QuantityReceived) as Lines, COUNT(DISTINCT PO_ReceiptHistoryHeader.ReceiptNo) as Count, COUNT(DISTINCT PO_ReceiptHistoryHeader.PurchaseOrderNo) as PoList')
            ->leftJoin('PO_ReceiptHistoryDetail', 'PO_ReceiptHistoryHeader.ReceiptNo', '=', 'PO_ReceiptHistoryDetail.ReceiptNo')
            ->where('PO_ReceiptHistoryHeader.ReceiptType', '!=', 'I')
            ->where('PO_ReceiptHistoryHeader.ReceiptDate', '>=', $a_date)
            ->where('PO_ReceiptHistoryHeader.ReceiptDate', '<=', $b_date)
            ->where('PO_ReceiptHistoryDetail.QuantityReceived', '!=', '0')
            ->where('PO_ReceiptHistoryHeader.Comment', 'like', 'Tally%')
            ->first();
    }


    public static function getTodaysReceipts($lastUpdate)
    {
        return self::select('PO_ReceiptHistoryDetail.ItemCode')
            ->selectRaw('SUM(PO_ReceiptHistoryDetail.QuantityReceived) as QuantityR')
            ->selectRaw('DATEADD(SECOND, CAST(dbo.PO_ReceiptHistoryHeader.TimeUpdated AS FLOAT) * 60 * 60, dbo.PO_ReceiptHistoryHeader.ReceiptDate) AS Updated')
            ->join('PO_ReceiptHistoryDetail', 'PO_ReceiptHistoryHeader.ReceiptNo', '=', 'PO_ReceiptHistoryDetail.ReceiptNo')
            ->groupBy('PO_ReceiptHistoryHeader.ReceiptDate', 'PO_ReceiptHistoryHeader.TimeUpdated', 'PO_ReceiptHistoryDetail.ItemCode')
            ->whereRaw('PO_ReceiptHistoryHeader.ReceiptDate = CURDATE()')
            ->whereRaw('DATEADD(SECOND, CAST(dbo.PO_ReceiptHistoryHeader.TimeUpdated AS FLOAT) * 60 * 60, dbo.PO_ReceiptHistoryHeader.ReceiptDate) > CONVERT(DATETIME, \'' . $lastUpdate . '\', 102)')
            ->havingRaw('SUM(PO_ReceiptHistoryDetail.QuantityReceived) > 0')
            ->orderBy('Updated')
            ->toRawSql();
    }
}
