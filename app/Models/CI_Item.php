<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CI_Item extends Model
{
    public    $incrementing = false;
    protected $connection   = 'digismart';
    protected $table        = 'CI_Item';
    protected $primaryKey   = 'ItemCode';
    protected $fillable     = ['ItemCode', 'ItemType', 'ItemCodeDesc', 'ExtendedDescriptionKey', 'Category1', 'ProductLine', 'Valuation'];

    public static function autocompleteItemCode($request)
    {
        return self::select('ItemCode')
            ->where('ItemCode', 'like', $request->term . '%')
            ->where('Valuation', '!=', '0')
            ->limit(25)
            ->orderBy('ItemCode')
            ->get()
            ->pluck('ItemCode');
    }

    public static function autocompleteProductCode($search_term)
    {
        return self::selectRaw('ItemCode as label, ItemCode as value')
            ->where('ItemCode', 'like', $search_term . '%')
            ->where('Valuation', '!=', '0')
            ->limit(25)
            ->orderBy('ItemCode')
            ->get();
    }

    public static function autocompleteProductCodeD9865($search_term)
    {
        return self::select('ItemCode')
            ->where('ItemCode', 'like', $search_term . '%')
            ->where('ItemCode', 'like', 'D9865%')
            ->where('Valuation', '!=', '0')
            ->limit(25)
            ->orderBy('ItemCode')
            ->get()
            ->pluck('ItemCode');
    }

    public static function autocompleteItemCodeCycle($request)
    {
        return self::selectRaw('ItemCode as id, ItemCode as text')
            ->where('ItemCode', 'like', $request->q . '%')
            ->limit($request->maxRows)
            ->get();
    }

    public static function validateProductCode($productCode)
    {
        if (!is_null($productCode)) {
            if (strpos($productCode, '%') === false) {
                return self::select('ItemCode')->where('ItemCode', '=', $productCode)->exists();
            } else {
                return true;
            }
        }
    }

    public static function validateProductCodeD9865($productCode)
    {
        if ($productCode) {

            return self::select('ItemCode')
                ->where('ItemCode', '=', $productCode)
                ->where('ItemCode', 'like', 'D9865%')
                ->exists();
        } else {
            return true;
        }

    }

    public static function isSerialized($productCode)
    {
        return self::where('ItemCode', '=', $productCode)
            ->where('Valuation', '=', '6')
            ->exists();
    }

    public static function getProductDescription($productCode)
    {
        return self::select('ItemCodeDesc')
            ->where('ItemCode', '=', $productCode)
            ->first()
            ->ItemCodeDesc;
    }

    public static function getExtendedDescription($productCode)
    {
        return self::select('CI_ExtendedDescription.ExtendedDescriptionText')
            ->join('CI_ExtendedDescription', 'CI_Item.ExtendedDescriptionKey', '=', 'CI_ExtendedDescription.ExtendedDescriptionKey')
            ->where('CI_Item.ItemCode', '=', $productCode)
            ->first()->ExtendedDescriptionText;
    }
}
