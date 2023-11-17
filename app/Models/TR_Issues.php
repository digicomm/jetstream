<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TR_Issues extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'TR_Issues';
    protected $fillable   = ['user_id', 'username', 'requested_by', 'assigned_to', 'warehouse_code', 'transaction_date', 'reason_code', 'product_code', 'unit_of_measure', 'bin_location', 'fifo_lifo', 'lot_number', 'tag_serial_number', 'quantity', 'tier', 'line', 'gl_acct'];

    public static function getIssues()
    {
        return self::select('TR_Issues.*')
            ->selectRaw('upper(SP_ReasonCodes.description) as reason')
            ->leftJoin('SP_ReasonCodes', 'TR_Issues.reason_code', '=', 'SP_ReasonCodes.reason_code')
            ->orderBy('id')
            ->where('user_id', request()->user()->id)
            ->get();
    }

    public static function getRecords()
    {
        return self::select('TR_Issues.*', 'SP_InventoryAccounts.inventory_account')
            ->leftJoin('SP_InventoryAccounts', 'TR_Issues.warehouse_code', '=', 'SP_InventoryAccounts.warehouse_code')
            ->orderBy('warehouse_code')
            ->orderBy('product_code')
            ->orderBy('fifo_lifo')
            ->where('user_id', request()->user()->id)
            ->get();
    }

    public static function deleteUserRecords()
    {
        return self::where('user_id', '=', request()->user()->id)
            ->delete();
    }

    public static function deleteSpecificRecords($records)
    {
        return self::whereIn('id', $records)->delete();
    }

    public static function getLastLine()
    {
        return self::select('line')
            ->where('user_id', request()->user()->id)
            ->orderByDesc('line')
            ->first();
    }


}
