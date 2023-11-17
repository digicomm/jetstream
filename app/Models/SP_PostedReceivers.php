<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SP_PostedReceivers extends Model
{
    use SoftDeletes;

    protected $connection = 'digismart';
    protected $table      = 'SP_PostedReceivers';
    protected $fillable   = ['receipt_id', 'arn_number', 'posted_on', 'line', 'po_line', 'product_code', 'quantity_received'];

    public static function getLastRecord()
    {
        return self::select('posted_on')
            ->orderByDesc('posted_on')
            ->limit(1)
            ->withTrashed()
            ->first();
    }

    public static function getReceivedMinusChina()
    {
        $include = DS_ChinaExcluded::getExcluded();

        return self::select('product_code')
            ->selectRaw('SUM(quantity_received) as quantity_received')
            ->where(function ($query) use ($include) {
                $query->where('product_code', 'not like', '%-G')
                    ->orWhereIn('product_code', $include);
            })
            ->groupBy('product_code')
            ->orderBy('product_code')
            ->get();
    }

    public static function getReceivedChina()
    {
        $china_excluded = DS_ChinaExcluded::getExcluded();
        return self::select('product_code')
            ->selectRaw('SUM(quantity_received) as quantity_received')
            ->where('product_code', 'like', '%-G')
            ->whereNotIn('product_code', $china_excluded)
            ->groupBy('product_code')
            ->orderBy('product_code')
            ->get();
    }

    public static function getTransferBackorders($fromPostedOn)
    {
        return self::select('SP_PostedReceivers.product_code', 'BU_StockReplenishment.to_product_code')
            ->selectRaw('SUM(quantity_received) as quantity_received, AVG(from_quantity) as from_quantity, AVG(to_quantity) as to_quantity')
            ->join('BU_StockReplenishment', 'SP_PostedReceivers.product_code', '=', 'BU_StockReplenishment.from_product_code')
            ->where('to_quantity', '<', '0')
            ->groupBy('SP_PostedReceivers.product_code', 'BU_StockReplenishment.from_product_code', 'BU_StockReplenishment.to_product_code')
            //->where('posted_on', '>=', $fromPostedOn)
            ->orderBy('product_code')
            ->withTrashed()
            ->toRawSql();
    }
}
