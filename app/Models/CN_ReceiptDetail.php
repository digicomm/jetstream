<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CN_ReceiptDetail extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'CN_ReceiptDetail';
    protected $fillable   = ['invoice_number', 'receipt_id', 'test_id', 'master_id', 'product_code', 'purchase_order', 'received'];

    public static function getReceiptQuantity($receipt_id, $product_code, $purchase_order)
    {
        return self::where('receipt_id', '=', $receipt_id)->where('product_code', '=', $product_code)->where('purchase_order', '=', $purchase_order)->first();
    }

    public function header()
    {
        return $this->belongsTo(CN_ReceiptHeader::class, 'id', 'receipt_id');
    }
}
