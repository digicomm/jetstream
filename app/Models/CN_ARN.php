<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CN_ARN extends Model
{
    use SoftDeletes;

    protected $connection = 'digismart';
    protected $table      = 'CN_ARN';
    protected $fillable   = ['invoice_number', 'master_id', 'product_code', 'purchase_order', 'quantity'];

    protected $casts = [
        'invoice_number' => 'string',
        'product_code' => 'string',
        'purchase_order' => 'integer',
        'quantity' => 'integer'
    ];

    public static function getList()
    {
        return self::select('purchase_order', 'product_code')
            ->selectRaw('SUM(quantity) as quantity')
            ->where('quantity', '>', '0')
            ->orderBy('purchase_order', 'asc')
            ->orderBy('product_code', 'asc')
            ->groupBy('purchase_order', 'product_code')
            ->get();
    }
}
