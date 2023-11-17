<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SP_OpenARNsNew extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'SP_OpenARNsNew';
    protected $fillable   = ['arn_number', 'date_entered', 'date_expected', 'line_number', 'po_line', 'product_code', 'purchase_order', 'quantity_expected', 'quantity_putaway', 'quantity_received', 'secondary_reference', 'special_instructions', 'status', 'supplier_name'];
    public    $timestamps = false;
}
