<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OM_HoldMismatch extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'digismart';
    protected $table      = 'OM_HoldMismatch';
    protected $fillable   = ['sage_sales_order', 'order_number', 'customer_no', 'sage_status', 'wms_status'];

    public static function getMismatches()
    {
        return self::select('sage_sales_order', 'order_number', 'customer_no', 'sage_status', 'wms_status', 'updated_at')
            ->whereColumn('sage_status', '!=', 'wms_status')
            ->orderBy('sage_sales_order')
            ->get();
    }
}
