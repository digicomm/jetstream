<?php

namespace App\Models;

use ErrorException;
use Illuminate\Database\Eloquent\Model;

class MAS_IM_ItemWarehouse extends Model
{
    protected $connection = 'mars';
    protected $table      = 'IM_ItemWarehouse';
    protected $casts      = [
        'QuantityOnHand' => 'float',
        'QuantityOnSalesOrder' => 'float',
        'QuantityOnBackorder' => 'float',
        'QuantityInShipping' => 'float',
        'available' => 'integer',
        'ItemCode' => 'string',
        'WarehouseCode' => 'string'
    ];

    public static function getNegativeQuantities()
    {
        return self::select('ItemCode', 'WarehouseCode', 'QuantityOnHand')->where('QuantityOnHand', '<', '0')->orderBy('ItemCode')->get();
    }

    public static function getPendingNegativeQuantities()
    {
        return self::select('ItemCode', 'WarehouseCode', 'QuantityOnHand', 'QuantityInShipping')
            ->where('QuantityInShipping', '>', '0')
            ->whereRaw('(QuantityOnHand - QuantityInShipping) < 0')
            ->orderBy('ItemCode');
    }

    public static function getChinaPartQOH($itemCode)
    {
        //return self::selectRaw( 'Sum(QuantityOnHand) - Sum(QuantityOnSalesOrder) - Sum(QuantityOnBackOrder) AS available' )->whereIn( 'ItemCode', [ $listCode, $itemCode ] )->whereNotIn( 'WarehouseCode', [ 'ASK', 'QUA', 'RMA', 'S&D', 'SAM', 'TEC', 'USE' ] )->first();
        return self::selectRaw('Sum(QuantityOnHand) - Sum(QuantityOnSalesOrder) - Sum(QuantityOnBackOrder) AS available')->whereIn('ItemCode', [$itemCode])->whereIn('WarehouseCode', ['ADE', 'AR2', 'GDE'])->first();
    }

    public static function getVarianceReportData()
    {
        return self::select('ItemCode', 'WarehouseCode')->selectRaw('(QuantityOnHand-QuantityInShipping) as OnHand')->get();

    }

    public static function getARNItemQty($product_code)
    {
        try {
            return self::selectRaw('(SUM(QuantityOnHand) - SUM(QuantityOnSalesOrder) - SUM(QuantityOnBackOrder)) as OnHand')
                ->where('ItemCode', $product_code)
                ->whereNotIn('WarehouseCode', ['ASK', 'QUA', 'RMA', 'S&D', 'SAM', 'TEC', 'USE'])
                ->groupBy('ItemCode')
                ->first()
                ->OnHand;
        } catch (ErrorException $e) {
            return "0.000000";
        }
    }

    public static function getAverageCostQUA($itemCode)
    {
        try {
            return self::select('AverageCost')->where('ItemCode', $itemCode)->where('WarehouseCode', 'QUA')->first()->AverageCost;
        } catch (ErrorException $e) {
            return self::select('AverageCost')->where('ItemCode', $itemCode)->first()->AverageCost;
        }

    }

    public static function getAverageCost($itemCode, $warehouse)
    {
        return self::select('AverageCost')->where('ItemCode', $itemCode)->where('WarehouseCode', $warehouse)->first()->AverageCost;
    }

    public static function getSalesAverageCost($items)
    {
        return self::select('ItemCode', 'WarehouseCode', 'AverageCost')->whereIn('ItemCode', $items)->get();

    }

    public static function getOnHand($itemCode)
    {
        return self::selectRaw('(SUM(QuantityOnHand) - SUM(QuantityOnSalesOrder) - SUM(QuantityOnBackOrder)) as onHand')->where(function ($query) use ($itemCode) {
            $query->where('ItemCode', strval($itemCode))->orWhere('ItemCode', strval($itemCode) . '-G');
        })->whereIn('WarehouseCode', ['000', 'ADE', 'AR2', 'GDE'])->first()->onHand;
    }

    public static function getOnHandBackorder($item_codes)
    {
        return self::select('ItemCode')
            ->selectRaw('(SUM(QuantityOnHand) - SUM(QuantityOnSalesOrder) - SUM(QuantityOnBackOrder)) as onHand')
            ->whereIn('ItemCode', $item_codes)
            ->whereIn('WarehouseCode', ['000', 'ALC', 'ADE', 'AR2', 'GDE'])
            ->groupBy('ItemCode')
            ->get();
    }


    public static function getAllOnHand()
    {
        return self::selectRaw('ItemCode, (SUM(QuantityOnHand) - SUM(QuantityOnSalesOrder) - SUM(QuantityOnBackOrder)) as onHand')->groupBy('ItemCode')->whereIn('WarehouseCode', ['000', 'ALC', 'GDE', 'ADE', 'AR2'])->get();
    }

    public static function getESOnHandNew($itemCode)
    {
        return self::selectRaw('SUM(QuantityOnHand) as QuantityOnHand')->where('ItemCode', $itemCode)->where('WarehouseCode', '000')->first()->QuantityOnHand;
    }

    public static function getESOnHandUsed($itemCode)
    {
        return self::selectRaw('SUM(QuantityOnHand) as QuantityOnHand')->where('ItemCode', $itemCode)->where('WarehouseCode', 'USE')->first()->QuantityOnHand;
    }

    public static function getShopifyQuantity($itemCode, $warehouse = "000")
    {
        return self::selectRaw('(QuantityOnHand-QuantityOnSalesOrder) as QuantityAvailable')->where('itemCode', $itemCode)->where('WarehouseCode', $warehouse)->first();
    }

    public static function getChinaBackorder($item_code)
    {
        return self::selectRaw('Sum(QuantityOnHand) AS SumOfQuantityOnHand, Sum(QuantityOnSalesOrder) AS SumOfQuantityOnSalesOrder, Sum(QuantityOnBackOrder) AS SumOfQuantityOnBackOrder')->where(function ($query) use ($item_code) {
            $query->where('ItemCode', $item_code)->orWhere('ItemCode', $item_code . '-G');
        })->whereIn('WarehouseCode', ['ADE', 'AR2', 'GDE', 'NLE'])->groupBy('ItemCode')->get();
    }

    public static function getReplenishmentQuantity()
    {
        $list = BU_StockReplenishment::getReplenishmentItems();
        $to_items = $list->pluck('to_product_code');
        $from_items = $list->pluck('from_product_code');
        $all_items = $to_items->merge($from_items);
        return self::select('ItemCode')
            ->selectRaw('(SUM(QuantityOnHand) - SUM(QuantityOnSalesOrder) - SUM(QuantityOnBackOrder)) as QuantityAvailable')
            ->whereIn('ItemCode', $all_items)
            ->groupBy('ItemCode')
            ->orderBy('ItemCode')
            ->get();
    }


}
