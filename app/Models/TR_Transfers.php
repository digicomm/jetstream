<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TR_Transfers extends Model
{
    //use SoftDeletes;
    protected $connection = 'digismart';
    protected $table      = 'TR_Transfers';
    protected $fillable   = ['user_id', 'username', 'constant', 'client', 'reason_code', 'notes', 'from_warehouse', 'to_warehouse', 'transaction_date', 'product_code', 'unit_of_measure', 'from_bin_location', 'to_bin_location', 'fifo_lifo', 'lot_number', 'tag_serial_number', 'quantity'];

    public static function getTransfers()
    {
        return self::select('TR_Transfers.*')
            ->selectRaw('upper(SP_ReasonCodes.description) as reason')
            ->leftJoin('SP_ReasonCodes', 'TR_Transfers.reason_code', '=', 'SP_ReasonCodes.reason_code')
            ->orderBy('id')
            ->where('user_id', request()->user()->id)
            ->get();
    }

    public static function getRecords()
    {
        return self::select('TR_Transfers.*', 'from_w.inventory_account', 'to_w.inventory_account')
            ->selectRaw('from_w.inventory_account as from_inventory_account, to_w.inventory_account as to_inventory_account')
            ->leftJoin('SP_InventoryAccounts as from_w', 'TR_Transfers.from_warehouse', '=', 'from_w.warehouse_code')
            ->leftJoin('SP_InventoryAccounts as to_w', 'TR_Transfers.to_warehouse', '=', 'to_w.warehouse_code')
            ->orderBy('from_warehouse')
            ->orderBy('to_warehouse')
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
}
