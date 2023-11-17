<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAS_RA_ReturnHeader extends Model
{
    protected $connection   = 'mars';
    protected $table        = 'RA_ReturnHeader';
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

    public static function getOpenList()
    {
        $year = date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 year'));
        return self::select('RMANo', 'RMAStatus', 'CustomerNo', 'BillToName')
            ->selectRaw('FORMAT(RMADate,\'MM/dd/yy\') as RMADate')
            ->where('RMADate', '>', $year)
            ->whereIn('RMAStatus', ['N', 'O', 'P'])
            ->get();
    }

    public static function getPartialList()
    {
        $year = date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 year'));
        return self::select('RMANo', 'RMAStatus', 'CustomerNo', 'BillToName')
            ->selectRaw('FORMAT(RMADate,\'MM/dd/yy\') as RMADate')
            ->where('RMADate', '>', $year)
            ->whereIn('RMAStatus', ['P'])
            ->get();
    }

    public function getHeader()
    {
        return self::select('RMANo', 'RMAStatus', 'CustomerNo', 'RMADate', 'ExpireDate')->where('RMANo', request()->rma)->first();
    }

    public function getReceivedHeader()
    {
        return self::setTable('RA_ReceiptsHistoryHeader')->select('RMANo', 'RMAStatus', 'CustomerNo', 'RMADate', 'BillToName')->where('RMANo', request()->rma)->first();
    }
}
