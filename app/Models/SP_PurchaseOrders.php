<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SP_PurchaseOrders extends Model
{
    protected $connection   = 'digismart';
    protected $table        = 'SP_PurchaseOrders';
    protected $fillable     = ['purchase_order', 'po_date', 'line_number', 'po_line', 'warehouse_code', 'product_code', 'quantity_ordered', 'quantity_received', 'quantity_arn', 'uom', 'status'];
    public    $incrementing = false;
    protected $keyType      = 'string';

    public static function getList()
    {
        return self::select('purchase_order')->distinct('purchase_order')->get();
    }

}
