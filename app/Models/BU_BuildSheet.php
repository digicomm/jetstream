<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BU_BuildSheet extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'BU_BuildSheet';
    protected $fillable   = ['user_id', 'constant', 'client', 'requested_by', 'assigned_to', 'transaction_date', 'reason_code', 'product_code', 'uom', 'bin_location', 'fifo_lifo', 'lot_number', 'tag_serial_number', 'quantity', 'tier', 'line', 'warehouse_code', 'type'];

    public static function clearTable()
    {
        self::where('user_id', '=', request()->user()->id)
            ->delete();
    }

    public static function generateCSV()
    {
        return self::where('user_id', request()->user()->id)
            ->where('type', '!=', 'K')
            ->orderBy('id')
            ->get();
    }

    public static function getBuildData()
    {
        return self::where('user_id', request()->user()->id)
            ->where('type', '!=', 'K')
            ->get();
    }
}
