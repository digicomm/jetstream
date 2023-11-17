<?php

namespace App\Models;

use App\Casts\ExcelDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OM_VerificationSage extends Model
{
    use HasFactory;

    protected $connection   = 'digismart';
    protected $table        = 'OM_VerificationSage';
    protected $fillable     = ['sage_sales_order', 'order_date', 'ship_expire', 'customer_no', 'ship_to_code', 'warehouse_code', 'po_line', 'product_code', 'quantity', 'status', 'invoiced', 'note'];
    public    $incrementing = false;
    protected $casts        = [
        'order_date' => ExcelDate::class,
        'ship_expire' => ExcelDate::class
    ];

    public static function getOrders()
    {
        return self::get();
    }
}
