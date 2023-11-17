<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SP_InventoryOnHandNew extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'SP_InventoryOnHandNew';
    protected $fillable   = ['bin_location', 'fifo_lifo', 'lot_number', 'product_code', 'quantity_on_hand', 'quantity_allocated', 'tag_serial_number', 'warehouse_code'];
}
