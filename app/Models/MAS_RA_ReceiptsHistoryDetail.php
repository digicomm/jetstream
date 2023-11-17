<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAS_RA_ReceiptsHistoryDetail extends Model
{
    protected $connection = 'mars';
    protected $table      = 'RA_ReceiptsHistoryDetail';

    public static function getDetail($rma)
    {
        return self::where('RMANo', str_pad($rma, 7, '0', STR_PAD_LEFT))
            ->where('QuantityReceived', '>', '0')
            ->get();
    }

    public function getReceivedDetail()
    {
        return self::select('InvoiceNo', 'ItemCode', 'ReturnReasonCode', 'ItemAction', 'QuantityReturned', 'QuantityReceived')
            ->where('RMANo', str_pad(request()->rma, 7, '0', STR_PAD_LEFT))
            ->orderBy('ItemCode', 'ASC')
            ->get();
    }
}
