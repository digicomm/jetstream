<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAS_RA_ReceiptsHistoryHeader extends Model
{
    protected $connection   = 'mars';
    protected $table        = 'RA_ReceiptsHistoryHeader';
    protected $primaryKey   = 'RMANo';
    public    $incrementing = false;
    protected $keyType      = 'string';

    public function details()
    {
        $open = $this->hasMany(MAS_RA_ReturnDetail::class, 'RMANo', 'RMANo')->select('InvoiceNo', 'ItemCode', 'ReturnReasonCode', 'ItemAction', 'QuantityReturned', 'QuantityReceived');
        $received = $this->hasMany(MAS_RA_ReceiptsHistoryDetail::class, 'RMANo', 'RMANo')->select('InvoiceNo', 'ItemCode', 'ReturnReasonCode', 'ItemAction', 'QuantityReturned', 'QuantityReceived');
        $union = $open->union($received);
        return $union;
    }

    public static function getReceivedListB()
    {
        $year = date('Y-m-d', strtotime(date('Y-m-d') . ' - 2 year'));
        return self::select('RMANo', 'RMAStatus', 'CustomerNo', 'BillToName')
            ->selectRaw('FORMAT(RMADate,\'MM/dd/yy\') as RMADate')
            ->where('RMADate', '>', $year)
            ->whereIn('RMAStatus', ['R'])
            ->get();
    }

    public static function getReceivedList($partials)
    {
        $year = date('Y-m-d', strtotime(date('Y-m-d') . ' - 2 year'));
        return self::select('RMANo', 'RMAStatus', 'CustomerNo', 'BillToName')
            ->selectRaw('FORMAT(RMADate,\'MM/dd/yy\') as RMADate')
            ->where('RMADate', '>', $year)
            ->whereIn('RMAStatus', ['R'])
            ->whereNotIn('RMANo', $partials)
            ->get();
    }

    public function getHeader()
    {
        return self::select('RMANo', 'RMAStatus', 'CustomerNo', 'RMADate', 'BillToName')->where('RMANo', request()->rma)->first();
    }
}
