<?php

namespace App\Models;

use App\Models\China\PurchaseOrderDetail;
use Illuminate\Database\Eloquent\Model;

class CN_Packing extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'CN_Packing';
    protected $fillable   = ['invoice_number', 'carton', 'carton_quantity', 'product_code', 'product_quantity'];

    public static function verifyPackingList($id)
    {
        $results = self::where('invoice', $id)->get();
        foreach ($results as $key => $value) {
            $checkPart = PurchaseOrderDetail::checkLine($value->product_code);
            $newPart = CN_ProductList::checkPart($value->product_code);
            $value->product_error = (count($newPart) === 0 ? 'warning' : null);
            $value->product_error = (count($checkPart) === 0 ? 'danger' : $value->product_error);
            $value->carton_error = ($value->carton === '' ? 'danger' : null);
            $value->quantity_error = ($value->product_quantity === '' || $value->product_quantity === '0' ? 'danger' : null);
        }
        return $results;
    }

    public static function getProductCartons($invoice_number, $product_code)
    {
        return self::where('invoice_number', '=', $invoice_number)
            ->where('product_code', $product_code)
            ->get();
    }

    public static function getBackorderProductCartons($invoice_number, $product_code)
    {
        return self::where('invoice_number', $invoice_number)->where('product_code', $product_code)->get();
    }

}
