<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAS_CI_Item extends Model
{
    public    $incrementing = false;
    protected $connection   = 'mars';
    protected $table        = 'CI_Item';
    protected $primaryKey   = 'ItemCode';
    protected $keyType      = 'string';

    public static function getItemCache()
    {
        return self::select('ItemCode', 'ItemType', 'ItemCodeDesc', 'ExtendedDescriptionKey', 'ProductLine', 'Valuation', 'Category1')
            ->where('ItemCode', 'not like', '/%')
            ->orderBy('ItemCode')
            ->get();
    }

    public static function getItemValuationList()
    {
        return self::selectRaw('ItemCode as Item, Valuation')->where('ItemCode', 'not like', '/%')->orderBy('ItemCode')->get();

    }

    public static function getTapsList()
    {
        return self::select('ItemCode')->where(function ($query) {
            $query->where('ItemCode', 'like', 'SG-TAP-_-__-%')->orWhere('ItemCode', 'like', 'SG-FST-_-__-%')->orWhere('ItemCode', 'like', 'SG-PASSIVES-%');
        })->get();
    }


    public static function getItemCodes()
    {
        return self::select('ItemCode')->where('ItemCode', 'like', request()->term . '%')->limit(25)->orderBy('ItemCode')->get()->pluck('ItemCode');
    }

    public static function validateItemCode()
    {
        if (strpos(request()->ItemCode, '%') === false) {
            return self::select('ItemCode')->where('ItemCode', request()->ItemCode)->count();
        } else {
            return 1;
        }

    }

    public function getDescription()
    {
        return $this->hasOne(CI_ExtendedDescription::class, 'ExtendedDescriptionKey', 'ExtendedDescriptionKey')->first()->ExtendedDescriptionText;
    }

    public static function getCycleAutocomplete()
    {
        return self::selectRaw('ItemCode as id, ItemCode as text')->where('ItemCode', 'like', request()->q . '%')->limit(request()->maxRows)->get();
    }

    public static function getItemDescription($itemCode)
    {
        return self::select('ItemCodeDesc')->where('ItemCode', $itemCode)->first()->ItemCodeDesc;
    }

    public static function getItemProdLine($itemCode)
    {
        return self::select('ProductLine')->where('ItemCode', $itemCode)->first()->ProductLine;
    }

    public static function getItemAndProdLine()
    {
        return self::selectRaw('\'2023\' as year, ItemCode as item_code, ProductLine as product_line, \'D\' as valuation')->where('ItemCode', 'not like', '/%')->orderBy('ItemCode')->offset(55000)->limit(5000)->get();
    }


}
