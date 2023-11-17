<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CC_Valuation extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'CC_Valuation';
    protected $fillable   = ['product_code', 'product_line', 'valuation', 'count1', 'count2', 'count3', 'count4', 'count5'];

    public static function getDashboardData($year)
    {
        $data = self::where('year', $year)
            ->select('valuation')
            ->selectRaw('count(valuation) as parts, count(count1) as count1, count(count2) as count2, count(count3) as count3')
            ->where('valuation', '!=', 'D')
            ->orderBy('valuation')
            ->groupBy('valuation')
            ->get();

        $x = 3;

        for ($i = 0; $i <= 2; $i++) {
            $data[$i]->total_counts = $data[$i]->parts * $x;
            $completed = 0;
            for ($y = 1; $y <= $x; $y++) {
                $count = "count" . $y;
                $completed += $data[$i]->$count;
            }
            $data[$i]->completed_counts = $completed;
            $data[$i]->to_count = $data[$i]->total_counts - $data[$i]->completed_counts;
            $data[$i]->percent_done = number_format(($data[$i]->completed_counts / $data[$i]->total_counts * 100), 1) - 0;
            $data[$i]->percent_to_count = number_format((100 - number_format($data[$i]->percent_done, 1)), 1) - 0;

            $x--;
        }

        return $data;
    }

    public static function getCount($itemCode, $close_date)
    {
        return self::where('product_code', $itemCode)->where('year', date('Y', strtotime($close_date)))->first()->count;
    }

    public static function getReportData($request)
    {
        if ($request->valuation == "ALL") {
            return self::where('year', $request->year)->orderBy('product_code')->get();
        } elseif ($request->valuation === "AC") {
            return self::where('year', $request->year)->where('valuation', '!=', 'D')->orderBy('product_code')->get();
        } else {
            return self::where('year', $request->year)->where('valuation', $request->valuation)->orderBy('product_code')->get();
        }
    }

    public static function getZeroReportData($request)
    {
        if ($request->valuation == "ALL") {
            return self::select('CC_Valuation.product_code', 'product_line', 'valuation', 'count', 'count1', 'count2', 'count3', 'count4', 'count5')
                ->selectRaw('SUM(SP_InventoryOnHand.quantity_on_hand) AS quantity')
                ->leftJoin('SP_InventoryOnHand', 'CC_Valuation.product_code', '=', 'SP_InventoryOnHand.product_code')
                ->groupBy('CC_Valuation.product_code', 'product_line', 'valuation', 'count', 'count1', 'count2', 'count3', 'count4', 'count5')
                ->where('year', $request->year)
                ->orderBy('valuation')
                ->orderBy('product_code')
                ->get();
        } elseif ($request->valuation === "AC") {
            return self::select('CC_Valuation.product_code', 'product_line', 'valuation', 'count', 'count1', 'count2', 'count3', 'count4', 'count5')
                ->selectRaw('SUM(SP_InventoryOnHand.quantity_on_hand) AS quantity')
                ->leftJoin('SP_InventoryOnHand', 'CC_Valuation.product_code', '=', 'SP_InventoryOnHand.product_code')
                ->groupBy('CC_Valuation.product_code', 'product_line', 'valuation', 'count', 'count1', 'count2', 'count3', 'count4', 'count5')
                ->where('year', $request->year)
                ->where('valuation', '!=', 'D')
                ->orderBy('valuation')
                ->orderBy('product_code')
                ->get();
        } else {
            return self::select('CC_Valuation.product_code', 'product_line', 'valuation', 'count', 'count1', 'count2', 'count3', 'count4', 'count5')
                ->selectRaw('SUM(SP_InventoryOnHand.quantity_on_hand) AS quantity')
                ->leftJoin('SP_InventoryOnHand', 'CC_Valuation.product_code', '=', 'SP_InventoryOnHand.product_code')
                ->groupBy('CC_Valuation.product_code', 'product_line', 'valuation', 'count', 'count1', 'count2', 'count3', 'count4', 'count5')
                ->where('year', $request->year)
                ->where('valuation', $request->valuation)
                ->orderBy('product_code')
                ->get();
        }
    }
}

