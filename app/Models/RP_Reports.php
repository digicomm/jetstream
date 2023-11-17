<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RP_Reports extends Model
{
    protected $connection   = 'digismart';
    protected $table        = "RP_Reports";
    protected $primaryKey   = "report";
    public    $incrementing = false;

    public static function getReportSignature($report)
    {
        return self::where('report', $report)->first()->signature;
    }


    public static function getReport($report)
    {
        return self::select('name')->where('report', $report)->first()->name;
    }

    public static function getList()
    {
        return self::select('description', 'frequency1', 'frequency2')->selectRaw('report as id, name as text')->get();
    }


    public static function searchList()
    {
        return self::where('report', 'like', '%' . request()->search . '%')
            ->orWhere('name', 'like', '%' . request()->search . '%')
            ->orWhere('description', 'like', '%' . request()->search . '%')
            ->get();
    }

    public static function getReportList()
    {
        return self::select('name', 'report')->where('manual', '1')->orderBy('name')->get();
    }

}
