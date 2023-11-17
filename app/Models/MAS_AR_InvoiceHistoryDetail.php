<?php

namespace App\Models;

use App\Models\MAS\AR_InvoiceHistoryDetail;
use Illuminate\Database\Eloquent\Model;

class MAS_AR_InvoiceHistoryDetail extends Model
{
    protected $connection = 'mars';
    protected $table      = 'AR_InvoiceHistoryDetail';

    public static function getLineCount($date)
    {
        return self::select('AR_InvoiceHistoryDetail.QuantityShipped')
            ->join('AR_InvoiceHistoryHeader', 'AR_InvoiceHistoryHeader.InvoiceNo', '=', 'AR_InvoiceHistoryDetail.InvoiceNo')
            ->where('ItemCode', 'not like', '/%')
            ->where('QuantityShipped', '<>', '0')
            ->where('InvoiceDate', $date)
            ->count();
    }

    public static function getShippedLines($startDate, $endDate)
    {
        return self::selectRaw('COUNT(AR_InvoiceHistoryDetail.QuantityShipped) as lines')
            ->where("AR_InvoiceHistoryDetail.ItemCode", "not like", "/%")
            ->where('AR_InvoiceHistoryDetail.QuantityShipped', '<>', '0')
            ->where('AR_InvoiceHistoryHeader.InvoiceDate', '>=', $startDate)
            ->where('AR_InvoiceHistoryHeader.InvoiceDate', '<=', $endDate)
            ->join('AR_InvoiceHistoryHeader', 'AR_InvoiceHistoryHeader.InvoiceNo', '=', 'AR_InvoiceHistoryDetail.InvoiceNo')
            ->first()->lines;
    }


}
