<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAS_RA_ReturnDetail extends Model
{
    protected $connection   = 'mars';
    protected $table        = 'RA_ReturnDetail';
    public    $incrementing = false;
    protected $keyType      = 'string';

    public function getDetail()
    {
        $received = MAS_RA_ReceiptsHistoryDetail::class::getReceivedDetail();
        return self::getOpenDetail()->union($received);
    }

    public function getOpenDetail()
    {
        return self::select('InvoiceNo', 'ItemCode', 'ReturnReasonCode', 'ItemAction', 'QuantityReturned', 'QuantityReceived')
            ->where('RMANo', str_pad(request()->rma, 7, '0', STR_PAD_LEFT))
            ->orderBy('ItemCode', 'ASC')
            ->get();
    }
}
