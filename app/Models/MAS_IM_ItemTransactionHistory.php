<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAS_IM_ItemTransactionHistory extends Model
{
    protected $connection = 'mars';
    protected $table      = 'IM_ItemTransactionHistory';
    public    $timestamps = false;


    public static function getTransactionHeader($entry_number, $journal_code)
    {
        if ($journal_code == 'SO') {
            return self::select('IM_ItemTransactionHistory.EntryNo', 'IM_ItemTransactionHistory.TransactionDate', 'IM_ItemTransactionHistory.IMTransactionEntryComment', 'IM_ItemTransactionHistory.VendorNo', 'IM_ItemTransactionHistory.ReceiptHistoryPurchaseOrderNo', 'SY_User.UserLogon', 'SY_User.FirstName', 'SY_User.LastName')
                ->leftJoin('SY_User', 'IM_ItemTransactionHistory.UserUpdatedKey', '=', 'SY_User.UserKey')
                ->where('IM_ItemTransactionHistory.EntryNo', 'like', $entry_number . '%')
                ->where('IM_ItemTransactionHistory.TransactionCode', '=', $journal_code)
                ->orderBy('ItemCode')
                ->first();
        } else {
            return self::select('IM_ItemTransactionHistory.EntryNo', 'IM_ItemTransactionHistory.TransactionDate', 'IM_ItemTransactionHistory.IMTransactionEntryComment', 'IM_ItemTransactionHistory.VendorNo', 'IM_ItemTransactionHistory.ReceiptHistoryPurchaseOrderNo', 'SY_User.UserLogon', 'SY_User.FirstName', 'SY_User.LastName')
                ->leftJoin('SY_User', 'IM_ItemTransactionHistory.UserUpdatedKey', '=', 'SY_User.UserKey')
                ->where('IM_ItemTransactionHistory.EntryNo', '=', $entry_number)
                ->where('IM_ItemTransactionHistory.TransactionCode', '=', $journal_code)
                ->orderBy('ItemCode')
                ->first();
        }


    }

    public static function getTransactionDetail($entry_number, $journal_code)
    {
        if ($journal_code == 'SO') {
            return self::select('IM_ItemTransactionHistory.ItemCode', 'IM_ItemTransactionHistory.WarehouseCode', 'IM_ItemTransactionHistory.TransactionQty', 'IM_ItemTransactionHistory.UnitCost', 'IM_ItemTransactionHistory.ExtendedCost')
                ->leftJoin('SY_User', 'IM_ItemTransactionHistory.UserUpdatedKey', '=', 'SY_User.UserKey')
                ->where('IM_ItemTransactionHistory.EntryNo', 'like', $entry_number . '%')
                ->where('IM_ItemTransactionHistory.TransactionCode', '=', $journal_code)
                ->orderBy('ItemCode')
                ->get();
        } else {
            return self::select('IM_ItemTransactionHistory.ItemCode', 'IM_ItemTransactionHistory.WarehouseCode', 'IM_ItemTransactionHistory.TransactionQty', 'IM_ItemTransactionHistory.UnitCost', 'IM_ItemTransactionHistory.ExtendedCost')
                ->leftJoin('SY_User', 'IM_ItemTransactionHistory.UserUpdatedKey', '=', 'SY_User.UserKey')
                ->where('IM_ItemTransactionHistory.EntryNo', '=', $entry_number)
                ->where('IM_ItemTransactionHistory.TransactionCode', '=', $journal_code)
                ->orderBy('ItemCode')
                ->get();
        }
    }


}
