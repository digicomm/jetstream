<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OPS_OrderEvents extends Model
{
    protected $connection = 'ops';
    protected $table      = 'OrderEvents';
    protected $fillable   = ['DateTime', 'OrderNumber', 'Event', 'UserID', 'UserName', 'BackorderCount'];
    public    $timestamps = false;


    public static function getOrderEventsReversed($order)
    {
        return self::select('*')
            ->where('OrderNumber', '=', $order)
            ->orderByDesc('DateTime')
            ->get();
    }

    public static function getPostedOrders($lastDate)
    {
        return self::select('OrderNumber')
            ->whereNotNull('BackorderCount')
            ->where('DateTime', '>=', $lastDate . ' 00:00:00')
            ->orderByDesc('OrderNumber')
            ->distinct()
            ->get();
    }
}
