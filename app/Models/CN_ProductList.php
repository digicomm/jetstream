<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CN_ProductList extends Model
{
    public    $incrementing = false;
    protected $connection   = 'digismart';
    protected $table        = 'CN_ProductList';
    protected $fillable     = ['product_code', 'china_code', 'lab_code'];
    protected $primaryKey   = 'product_code';
    protected $keyType      = 'string';

    public static function getLookupPart($part)
    {
        return self::select('product_code', 'china_code', 'lab_code')->where('china_code', $part)->first();
    }

    public static function getAutoComplete()
    {
        return self::selectRaw('listCode as value, listCode as label')
            ->where('listCode', 'like', request()->term . '%')
            ->orderBy('listCode')
            ->limit(10)
            ->get();
    }

    public static function checkPart($part)
    {
        return self::select('china_code')->where('china_code', $part)->get();
    }

    public static function chinaList($query)
    {
        return self::select('listCode')->where('listCode', 'like', $query . '%')->limit(10)->orderBy('listCode')->get();
    }

    public static function autocompleteProductCode($product_code)
    {
        return self::selectRaw('china_code as value, china_code as label')
            ->where('china_code', 'like', $product_code . '%')
            ->orderBy('china_code')
            ->limit(10)
            ->get();
    }

    public static function getProductCode($china_code)
    {
        return self::select('product_code')
            ->where('china_code', '=', $china_code)
            ->first();
    }
}
