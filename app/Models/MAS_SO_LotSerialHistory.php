<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAS_SO_LotSerialHistory extends Model
{
    protected $connection = 'mars';
    protected $table      = 'SO_LotSerialHistory';

    public static function getRMASerial($cm, $itemCode)
    {

        return self::select('LotSerialNo')
            ->where('InvoiceNo', $cm)
            ->where('ItemCode', $itemCode)
            ->get();
    }
}
