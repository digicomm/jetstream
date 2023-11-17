<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BU_LabArchive extends Model
{
    use SoftDeletes;

    protected $connection = 'digismart';
    protected $table      = 'BU_LabArchive';
    protected $fillable   = ['id', 'special_instructions', 'sage_sales_order', 'order_number', 'customer_no', 'ship_to_city', 'promise_date', 'expected_date', 'po_line', 'product_code', 'quantity_ordered', 'quantity_shipped', 'quantity_open', 'quantity_building', 'quantity_available', 'quantity_allocated', 'status', 'assigned_to', 'priority', 'allocation', 'created_at', 'updated_at', 'deleted_at', 'uid', 'rowData'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
