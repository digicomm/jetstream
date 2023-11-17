<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAS_EC_GeneralCrossReference extends Model
{
    protected $connection   = 'mars';
    protected $table        = 'EC_GeneralCrossReference';
    public    $incrementing = false;

    public static function getCharterPricing()
    {
        return self::select('CrossReferenceList', 'MASValue', 'CrossReferenceCode', 'Description')
            ->where('CrossReferenceList', '=', 'CHARTER PRICE')
            ->get();
    }

}
