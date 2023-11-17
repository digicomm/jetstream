<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CN_Invoice extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'CN_Invoice';
    protected $fillable   = ['invoice_number', 'line', 'product_code', 'product_description', 'quantity'];

    public static function getLabelList()
    {
        return self::select('itemCode')
            ->selectRaw('SUM(itemQty) as totalQty')
            ->where('invoiceNo', request()->invoice_number)->groupBy('itemCode')->orderBy('itemCode', 'DESC')->get();
    }

    public static function getReportList($invoice_number)
    {
        return self::where('invoice_number', $invoice_number)
            ->select('product_code')->selectRaw('SUM(quantity) as total_quantity')->groupBy('product_code')->orderBy('product_code')->get();
    }

    public static function verifyInvoice($id)
    {
        $results = self::where('invoice', $id)->get();
        foreach ($results as $key => $value) {
            $checkPart = MAS_PO_PurchaseOrderDetail::checkChinaLine($value->product_code);
            $newPart = CN_ProductList::checkPart($value->product_code);
            $value->line_error = ($value->line == '' ? 'bg-danger-light' : null);
            $value->product_error = (count($newPart) == 0 ? 'bg-warning-light' : null);
            $value->product_error = (count($checkPart) == 0 ? 'bg-danger-light' : $value->product_error);
            $value->quantity_error = ($value->quantity == "" || $value->quantity == "0" ? ' bg-danger-light' : null);
        }
        return $results;
    }

    public static function getInvoiceLine($id, $line)
    {
        return self::where('invoice_number', $id)->where('line', $line)->first();
    }


}
