<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TR_Adjustments extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'TR_Adjustments';
    protected $fillable   = ['user_id', 'requested_by', 'assigned_to', 'warehouse_code', 'transaction_date', 'reason_code', 'product_code', 'uom', 'bin_location', 'fifo_lifo', 'lot_number', 'tag_serial_number', 'quantity', 'line'];

    public static function getAdjustments()
    {
        return self::select('TR_Adjustments.*')
            ->selectRaw('upper(SP_ReasonCodes.description) as reason')
            ->leftJoin('SP_ReasonCodes', 'TR_Adjustments.reason_code', '=', 'SP_ReasonCodes.reason_code')
            ->orderBy('id')
            ->where('user_id', request()->user()->id)
            ->get();
    }

    public static function getRecords()
    {
        return self::select('TR_Adjustments.*')
            ->selectRaw('SP_InventoryAccounts.inventory_account')
            ->leftJoin('SP_InventoryAccounts', 'TR_Adjustments.warehouse_code', '=', 'SP_InventoryAccounts.warehouse_code')
            ->where('user_id', request()->user()->id)
            ->orderBy('line')
            ->get();
    }

    public static function getLastLine()
    {
        return self::select('line')
            ->where('user_id', request()->user()->id)
            ->orderByDesc('line')
            ->first();
    }

    public static function clearTable()
    {
        return self::where('user_id', request()->user()->id)->delete();
    }
}
