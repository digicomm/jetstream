<?php

namespace App\Models;

use App\Casts\FifoDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class SP_InventoryOnHandC extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'SP_InventoryOnHandC';

    public static function getAllQUA() {
        return self::select('product_code')
            ->selectRaw('SUM(CASE WHEN warehouse_code like "QUA" AND bin_location like "QUA" THEN quantity_on_hand ELSE 0 END) AS missing, SUM(CASE WHEN warehouse_code like "QUA" AND bin_location not like "QUA" and bin_location not like "RMA" THEN quantity_on_hand ELSE 0 END) AS qua, SUM(CASE WHEN warehouse_code like "QUA" AND bin_location like "RMA" THEN quantity_on_hand ELSE 0 END) AS rma')
            ->where('warehouse_code','=','QUA')
            ->groupBy('product_code')
            ->orderBy('product_code')
            ->get();
    }
}
