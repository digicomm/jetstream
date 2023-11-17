<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SN_Header extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'SN_Header';
    protected $fillable   = ['type', 'customer_code', 'sage_sales_order', 'po_line', 'product_code', 'ship_quantity', 'scan_quantity', 'status', 'initials'];

    public static function getD9865SatelliteOpenList()
    {
        return self::select('SN_Header.id', 'sage_sales_order', 'po_line', 'product_code', 'ship_quantity', 'scan_quantity', 'status', 'initials')
            ->selectRaw('COUNT(SN_D9865Satellite.tag_serial_number) as scanned')
            ->where('type', '=', 'D9865Satellite')
            ->where('status', '=', '0')
            ->leftJoin('SN_D9865Satellite', 'SN_Header.id', '=', 'SN_D9865Satellite.header_id')
            ->groupBy('SN_Header.id', 'sage_sales_order', 'po_line', 'product_code', 'ship_quantity', 'scan_quantity', 'status', 'initials')
            ->orderByDesc('id')
            ->get();
    }

    public static function getD9865SatelliteClosedList()
    {
        return self::select('id', 'sage_sales_order', 'po_line', 'product_code', 'ship_quantity', 'scan_quantity', 'status', 'initials', 'created_at')
            ->where('type', '=', 'D9865Satellite')
            ->where('status', '=', '1')
            ->orderByDesc('id')
            ->get();
    }

    public function getD9865SatelliteDetails()
    {
        return $this->hasMany(SN_D9865Satellite::class, 'header_id', 'id')->orderBy('id', request()->order ? request()->order : 'asc');
    }
}
