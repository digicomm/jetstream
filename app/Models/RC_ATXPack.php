<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RC_ATXPack extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'RC_ATXPack';
    protected $fillable   = ['shipment', 'order', 'line', 'rel', 'product_code', 'description', 'quantity_shipped', 'purchase_order', 'packing_slip'];
}
