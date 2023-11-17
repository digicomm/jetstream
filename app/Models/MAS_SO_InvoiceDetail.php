<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAS_SO_InvoiceDetail extends Model
{
    protected $connection = 'mars';
    protected $table      = 'SO_InvoiceDetail';

    public static function getEndOfDayDashboardInfo(): array
    {
        $sphere_lines = self::where('SO_InvoiceHeader.UDF_WMS_NO', '<>', '')
            ->leftJoin('SO_InvoiceHeader', 'SO_InvoiceDetail.InvoiceNo', '=', 'SO_InvoiceHeader.InvoiceNo')
            ->where('SO_InvoiceHeader.InvoiceType', '=', 'IN')
            ->count('SO_InvoiceDetail.InvoiceNo');
        $manual_lines = self::where('SO_InvoiceHeader.UDF_WMS_NO', '=', '')
            ->leftJoin('SO_InvoiceHeader', 'SO_InvoiceDetail.InvoiceNo', '=', 'SO_InvoiceHeader.InvoiceNo')
            ->where('SO_InvoiceHeader.InvoiceType', '=', 'IN')
            ->count('SO_InvoiceDetail.InvoiceNo');
        $cm_lines = self::where('SO_InvoiceHeader.InvoiceType', '=', 'CM')
            ->leftJoin('SO_InvoiceHeader', 'SO_InvoiceDetail.InvoiceNo', '=', 'SO_InvoiceHeader.InvoiceNo')
            ->count('SO_InvoiceDetail.InvoiceNo');
        $total_lines = $sphere_lines + $manual_lines + $cm_lines;


        return array('sphere_lines' => $sphere_lines, 'manual_lines' => $manual_lines, 'cm_lines' => $cm_lines, 'total_lines' => $total_lines);
    }

    public static function getLines()
    {
        return self::select('ItemCode')->where('QuantityShipped', '>', '0')->where('ItemCode', 'not like', '/%')->count();
    }
}
