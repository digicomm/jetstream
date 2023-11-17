<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CC_Detail extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'CC_Detail';
    protected $fillable   = ['header_id', 'date', 'product_code', 'product_description', 'warehouse_code', 'bin_location', 'quantity_on_hand', 'quantity_allocated', 'cost', 'count_previous', 'count_current', 'final_quantity_on_hand', 'final_quantity_allocated', 'final_count', 'final_cost', 'notes', 'count_dollar', 'final_count_dollar', 'final_quantity_on_hand_dollar', 'final_variance', 'final_variance_absolute', 'final_variance_absolute_dollar', 'final_variance_dollar', 'variance', 'variance_absolute', 'variance_absolute_dollar', 'variance_dollar'];

    public static function getVarianceTotals($id)
    {
        return self::selectRaw('sum(`final_quantity_on_hand`) as final_quantity_on_hand, sum(`final_quantity_on_hand_dollar`) as final_quantity_on_hand_dollar, sum(`count_current`) as count_current, sum(`count_dollar`) as count_dollar, sum(`variance`) as variance, sum(`variance_absolute`) as variance_absolute, sum(`variance_absolute_dollar`) as variance_absolute_dollar, sum(`variance_dollar`) as variance_dollar, sum(`final_count`) as final_count, sum(`final_count_dollar`) as final_count_dollar, sum(`final_variance`) as final_variance, sum(`final_variance_dollar`) as final_variance_dollar, sum(`final_variance_absolute_dollar`) as final_variance_absolute_dollar, sum(`final_variance_absolute`) as final_variance_absolute')
            ->where('header_id', $id)
            ->first();
    }

    public static function moveCurrentCountToFinalCount($id)
    {
        return self::where('header_id', $id)
            ->update(['final_count' => DB::raw("`count_current`")]);
    }

    public static function moveCurrentCountToPreviousCount($id)
    {
        return self::where('header_id', $id)->update(['count_previous' => DB::raw("`count_current`")]);
    }

    public static function getSummaryMonth($year, $month)
    {
        $date = strtotime($year . '-' . $month . '-01');
        return self::selectRaw('sum(`final_quantity_on_hand`) as final_quantity_on_hand, sum(`final_quantity_on_hand_dollar`) as final_quantity_on_hand_dollar, sum(`count_current`) as count_current, sum(`count_dollar`) as count_dollar, sum(`variance`) as variance, sum(`variance_absolute`) as variance_absolute, sum(`variance_absolute_dollar`) as variance_absolute_dollar, sum(`variance_dollar`) as variance_dollar, sum(`final_count`) as final_count, sum(`final_count_dollar`) as final_count_dollar, sum(`final_variance`) as final_variance, sum(`final_variance_dollar`) as final_variance_dollar, sum(`final_variance_absolute_dollar`) as final_variance_absolute_dollar, sum(`final_variance_absolute`) as final_variance_absolute, count(distinct(`product_code`)) as item_count')
            ->leftJoin('CC_Header', 'CC_Header.id', '=', 'CC_Detail.header_id')
            ->whereDate('closed_at', '>=', date('Y-m-d', $date))
            ->whereDate('closed_at', '<=', date('Y-m-t', $date))
            ->first();
    }

    public static function getDetailMonth($year, $month)
    {
        $date = strtotime($year . '-' . $month . '-01');
        return self::select('CC_Header.closed_at', 'CC_Detail.header_id', 'CC_Detail.product_code', 'CC_Detail.warehouse_code', 'CC_Detail.bin_location', 'CC_Detail.final_quantity_on_hand', 'CC_Detail.final_quantity_on_hand_dollar', 'CC_Detail.final_count', 'CC_Detail.final_count_dollar', 'CC_Detail.final_variance', 'CC_Detail.final_variance_dollar', 'CC_Detail.final_variance_absolute', 'CC_Detail.final_variance_absolute_dollar', 'CC_Detail.notes')
            ->leftJoin('CC_Header', 'CC_Header.id', '=', 'CC_Detail.header_id')
            ->whereDate('closed_at', '>=', date('Y-m-d', $date))
            ->whereDate('closed_at', '<=', date('Y-m-t', $date))
            ->orderBy('CC_Header.closed_at')
            ->orderBy('CC_Detail.header_id')
            ->orderBy('CC_Detail.product_code')
            ->orderBy('CC_Detail.warehouse_code')
            ->orderBy('CC_Detail.bin_location')
            ->get();
    }

    public static function getSummaryYear($year)
    {
        return self::selectRaw('sum(`final_quantity_on_hand`) as final_quantity_on_hand, sum(`final_quantity_on_hand_dollar`) as final_quantity_on_hand_dollar, sum(`count_current`) as count_current, sum(`count_dollar`) as count_dollar, sum(`variance`) as variance, sum(`variance_absolute`) as variance_absolute, sum(`variance_absolute_dollar`) as variance_absolute_dollar, sum(`variance_dollar`) as variance_dollar, sum(`final_count`) as final_count, sum(`final_count_dollar`) as final_count_dollar, sum(`final_variance`) as final_variance, sum(`final_variance_dollar`) as final_variance_dollar, sum(`final_variance_absolute_dollar`) as final_variance_absolute_dollar, sum(`final_variance_absolute`) as final_variance_absolute, count(distinct(`product_code`)) as item_count')
            ->leftJoin('CC_Header', 'CC_Header.id', '=', 'CC_Detail.header_id')
            ->whereBetween('closed_at', [$year . '-01-01 00:00:00', $year . '-12-31 23:59:59'])
            ->first();
    }

    public static function getDetailYear($year)
    {
        return self::select('CC_Header.closed_at', 'CC_Detail.header_id', 'CC_Detail.product_code', 'CC_Detail.warehouse_code', 'CC_Detail.bin_location', 'CC_Detail.final_quantity_on_hand', 'CC_Detail.final_quantity_on_hand_dollar', 'CC_Detail.final_count', 'CC_Detail.final_count_dollar', 'CC_Detail.final_variance', 'CC_Detail.final_variance_dollar', 'CC_Detail.final_variance_absolute', 'CC_Detail.final_variance_absolute_dollar', 'CC_Detail.notes')
            ->leftJoin('CC_Header', 'CC_Header.id', '=', 'CC_Detail.header_id')
            ->whereBetween('closed_at', [$year . '-01-01 00:00:00', $year . '-12-31 23:59:59'])
            ->orderBy('CC_Header.closed_at')
            ->orderBy('CC_Detail.header_id')
            ->orderBy('CC_Detail.product_code')
            ->orderBy('CC_Detail.warehouse_code')
            ->orderBy('CC_Detail.bin_location')
            ->get();
    }
}
