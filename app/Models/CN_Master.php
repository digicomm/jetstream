<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CN_Master extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'CN_Master';
    protected $fillable   = ['invoice', 'master_id', 'product_code', 'quantity', 'purchase_order', 'warehouse', 'checked', 'received'];
    protected $casts      = [
        'checked' => 'integer',
        'check_remaining' => 'integer',
        'purchase_order' => 'integer',
        'quantity' => 'integer',
        'received' => 'integer',
        'receive_remaining' => 'integer'
    ];

    public static function searchProducts()
    {
        return self::select('CN_Header.vendor_code', 'CN_Header.invoice_number', 'CN_Header.ship_date', 'CN_Master.product_code', 'CN_Master.purchase_order')
            ->join('CN_Header', 'CN_Header.invoice_number', '=', 'CN_Master.invoice_number')
            ->selectRaw('SUM(CN_Master.quantity) as quantity, SUM(received) as received, SUM(receive_remaining) as receive_remaining,SUM(checked) as checked, SUM(check_remaining) as check_remaining, CONCAT(CN_Master.product_code,"-G") as product_code_g')
            ->where('CN_Header.status', '=', 'O')
            ->groupBy('CN_Header.vendor_code', 'CN_Header.invoice_number', 'CN_Header.ship_date', 'CN_Master.product_code', 'CN_Master.purchase_order')
            ->orderBy('CN_Header.ship_date')
            ->orderBy('CN_Master.product_code')
            ->get();
    }

    public static function getBackorders()
    {
        return self::select('CN_Header.vendor_code', 'CN_Header.invoice_number', 'CN_Header.ship_date', 'CN_Master.product_code')
            ->join('CN_Header', 'CN_Header.invoice_number', '=', 'CN_Master.invoice_number')
            ->selectRaw('SUM(CN_Master.quantity) as quantity, SUM(received) as received, SUM(receive_remaining) as receive_remaining, CONCAT(CN_Master.product_code,"-G") as product_code_g')
            ->where('CN_Header.status', '=', 'O')
            ->having('receive_remaining', '>', '0')
            ->groupBy('CN_Header.vendor_code', 'CN_Header.invoice_number', 'CN_Header.ship_date', 'CN_Master.product_code')
            ->orderBy('CN_Header.ship_date')
            ->orderBy('CN_Master.product_code')
            ->get();
    }

    public static function getTestPoQty($invoice_number, $product_code, $purchase_order)
    {
        return self::where('invoice_number', $invoice_number)
            ->where('product_code', $product_code)
            ->where('purchase_order', $purchase_order)
            ->first();
    }

    public static function getProductQty($invoice_number, $product_code)
    {
        return self::select('product_code')
            ->selectRaw('SUM(receive_remaining) as remaining')
            ->where('invoice_number', '=', $invoice_number)
            ->where('product_code', '=', $product_code)
            ->groupBy('product_code')->first();
    }

    public static function getReceiptInfo($id)
    {
        return self::where('invoiceNo', request()->invoiceNo)->where('id', $id)->first();
    }

    public static function getCheckInQuantity($invoice_number, $product_code)
    {
        return self::selectRaw('SUM(check_remaining) as remaining, SUM(quantity) as quantity, SUM(checked) as checked')
            ->where('invoice_number', '=', $invoice_number)
            ->where('product_code', '=', $product_code)
            ->groupBy('product_code')
            ->first();
    }

    public static function getOrderQuantity($invoice_number, $product_code)
    {
        return self::selectRaw('SUM(quantity) as quantity')
            ->where('invoice_number', '=', $invoice_number)
            ->where('product_code', '=', $product_code)
            ->groupBy('product_code')
            ->first();
    }


    public static function fixReceipts()
    {
        return self::select('CN_Master.id')
            ->selectRaw('IF(SUM(CN_ReceiptDetail.quantity) IS NULL,0,SUM(CN_ReceiptDetail.quantity)) as received')
            ->leftJoin('CN_ReceiptDetail', 'CN_Master.id', '=', 'CN_ReceiptDetail.master_id')
            ->where('CN_ReceiptDetail.posted', '=', TRUE)
            ->groupBy('CN_Master.id')
            ->orderBY('CN_Master.id')
            ->get();
    }


}
