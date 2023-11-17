<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SN_Gainmakers extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'SN_Gainmakers';
    protected $fillable   = ['scan_id', 'source', 'sage_sales_order', 'po_line', 'customer_code', 'product_code', 'product_code_used', 'tag_serial_number', 'quantity', 'date', 'notes', 'archive'];

}
