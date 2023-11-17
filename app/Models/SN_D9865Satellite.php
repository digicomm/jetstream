<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SN_D9865Satellite extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'SN_D9865Satellite';
    protected $fillable   = ['header_id', 'product_code', 'sage_sales_order', 'po_line', 'quantity', 'tag_serial_number', 'tag_mac_address', 'tag_ua'];

    public static function startOver($header_id)
    {
        return self::where('header_id', '=', $header_id)
            ->delete();
    }
}
