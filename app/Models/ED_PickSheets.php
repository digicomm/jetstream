<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ED_PickSheets extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'ED_PickSheets';
    protected $fillable   = ['date', 'pick_sheet', 'user_id'];
    protected $casts      = ['date' => 'date'];

    public static function getPickSheets()
    {
        return self::select('id', 'pick_sheet', 'created_at')
            ->selectRaw('ROUND(timestampdiff(SECOND, created_at, CURRENT_TIMESTAMP)) as scanned')
            ->where('date', '=', date('Y-m-d'))
            ->orderBy('id')
            ->get();
    }

    public static function getNotPosted()
    {
        $posted = SP_PostedShipments::getPostedOrderNumbers();
        return self::select('pick_sheet')
            ->where('date', '=', date('Y-m-d-'))
            ->whereNotIn('pick_sheet', $posted)
            ->count();
    }

    public static function getPickSheetCount()
    {
        return self::select('pick_sheet')
            ->where('date', '=', date('Y-m-d'))
            ->count();
    }

    public static function checkForMissing($orderNo)
    {
        return self::where('date', '=', date('Y-m-d'))
            ->where('pick_sheet', $orderNo)->count();
    }
}
