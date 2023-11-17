<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SP_OpenOrdersNew extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'SP_OpenOrdersNew';
    protected $fillable   = ['backorder_count', 'bill_to_code', 'build', 'customer_name', 'customer_part_number', 'date_order_entered', 'date_requested', 'hold_status', 'line_number', 'order_number', 'order_lock_status', 'order_status', 'order_status_code', 'po_line', 'print_date_time', 'printed', 'product_code', 'promise_date', 'purchase_order_number', 'quantity_allocated', 'quantity_ordered', 'sage_sales_order', 'ship_to_city', 'ship_to_code', 'special_instructions', 'special_instructions_2', 'unit_price', 'warehouse_code'];
}
