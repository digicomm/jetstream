<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CN_POList extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'CN_POList';
    protected $fillable   = ['invoice_number', 'start', 'end', 'purchase_order'];

    public static function getPoList($id)
    {
        return self::where('invoice_number', $id)->get();
    }

    public static function listPurchaseOrders($invoice_number)
    {
        return self::select('purchase_order')->where('invoice_number', '=', $invoice_number)->orderBy('purchase_order')->get();
    }

}
