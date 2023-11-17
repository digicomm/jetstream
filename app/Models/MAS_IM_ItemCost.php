<?php

namespace App\Models;

use ErrorException;
use Illuminate\Database\Eloquent\Model;

class MAS_IM_ItemCost extends Model
{
    protected $connection = 'mars';
    protected $table      = 'IM_ItemCost';

    public static function getCost($itemCode, $serial)
    {
        return self::select('UnitCost')->where('ItemCode', $itemCode)->where('LotSerialNo', $serial)->orderBy('TransactionDate', 'DESC')->limit(1)->first()->UnitCost;
    }

    public static function getWarehouse($itemCode, $serial)
    {
        try {
            return self::select('WarehouseCode')->where('ItemCode', $itemCode)->where('LotSerialNo', $serial)->orderBy('TransactionDate', 'ASC')->limit(1)->first()->WarehouseCode;
        } catch (ErrorException $e) {
            return "000";
        }
    }
}
