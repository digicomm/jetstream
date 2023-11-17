<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OM_VerificationWMS extends Model
{
    use HasFactory;

    protected $connection   = 'digismart';
    protected $table        = 'OM_VerificationWMS';
    protected $fillable     = ['order_number', 'sage_sales_order', 'line_number', 'po_line', 'warehouse_wms', 'warehouse_sage', 'product_code_wms', 'product_code_sage', 'quantity_wms', 'quantity_sage', 'order_status', 'note'];
    public    $incrementing = false;

    public static function getOrders()
    {
        return self::get();
    }
}
