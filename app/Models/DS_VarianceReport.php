<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DS_VarianceReport extends Model
{
    protected $connection = 'digismart';

    protected $table    = 'DS_VarianceReport';
    protected $fillable = ['product_code', 'warehouse_code', 'sage', 'wms'];

    public static function getVarianceReport()
    {
        return self::select('product_code', 'sage', 'wms', 'warehouse_code', 'variance')
            ->orderBy('product_code')
            ->orderBy('warehouse_code')
            ->get();
    }


}
