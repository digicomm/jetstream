<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BU_StockReplenishment extends Model
{
    use SoftDeletes;

    protected $connection = 'digismart';
    protected $table      = 'BU_StockReplenishment';
    protected $fillable   = ['to_product_code', 'from_product_code', 'case_quantity', 'factor', 'action', 'notes'];

    public static function getReplenishmentItems()
    {
        return self::select('id', 'to_product_code', 'from_product_code')
            ->orderBy('to_product_code')
            ->get();
    }

    public static function getReplenishmentList()
    {
        return self::select('to_product_code', 'from_product_code', 'action', 'notes', 'factor')
            ->selectRaw('(to_quantity * -1) as quantity_needed, CASE WHEN (to_quantity*-1) > from_quantity THEN from_quantity ELSE CEIL(CEIL((to_quantity*-1)/factor)/case_quantity)*case_quantity END AS transfer_quantity, (case_quantity*factor) as factor_multiple, CASE WHEN (to_quantity*-1) < from_quantity THEN CONCAT(from_quantity," AVAILABLE") ELSE "" END AS quantity_note')
            ->where('to_quantity', '<', '0')
            ->where('from_quantity', '>', '0')
            ->orderBy('to_product_code')
            ->get();
    }
}
