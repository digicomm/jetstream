<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DS_ChinaExcluded extends Model
{
    public    $incrementing = false;
    protected $connection   = 'digismart';
    protected $table        = 'DS_ChinaExcluded';
    protected $primaryKey   = 'product_code';
    protected $keyType      = 'string';

    public static function getExcluded()
    {
        return self::select('product_code')
            ->orderBy('product_code')
            ->get();
    }
}
