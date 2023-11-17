<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DS_ASNIncoming extends Model
{
    protected $connection = 'digismart';

    protected $table = 'DS_ASNIncoming';

    public static function getASNSN($request)
    {
        return self::select('purchaseOrder', 'itemCode', 'shipped', 'serial', 'sageLine')
            ->where('secRef', $request->reference)
            ->whereNull('subLine')
            ->get();
    }

    public static function getASNPO($request)
    {
        return self::select('purchaseOrder')
            ->where('secRef', $request->reference)
            ->first();
    }

    public static function getMissingARNs()
    {
        return self::select('purchaseOrder', 'salesOrder', 'shipSet', 'secRef', 'line', 'sageLine', 'itemCode', 'ordered', 'shipped', 'dateSent')->orderBy('dateSent')
            ->groupBy('purchaseOrder', 'salesOrder', 'shipSet', 'secRef', 'line', 'sageLine', 'itemCode', 'ordered', 'shipped', 'dateSent')
            ->where('subLine', NULL)
            ->where('status', '1')
            ->get();
    }

    public static function getNewLines()
    {
        return self::where('subLine', NULL)->where('sageLine', NULL)->where('status', '!=', '2')->get();
    }

    public static function getNewOrders()
    {
        return self::select('id', 'secRef')->where('subLine', NULL)->where('status', '!=', '2')->groupBy('secRef', 'id')->orderBy('secRef')->get();
    }
}
