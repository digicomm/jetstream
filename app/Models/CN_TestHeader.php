<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CN_TestHeader extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'CN_TestHeader';
    protected $fillable   = ['invoice_number', 'type', 'invoice_test'];

    public static function getLastTest($invoice_number)
    {
        return self::where('invoice_number', '=', $invoice_number)->count();
    }

    public function listTestData()
    {
        return $this->hasMany(CN_TestDetail::class, 'test_id', 'id')->select('id', 'invoice_number', 'test_id', 'product_code', 'quantity', 'initials', 'test_date');
    }

    public function listPoList()
    {
        return $this->hasMany(CN_POList::class, 'invoice_number', 'invoice_number')->select('purchase_order')->orderBy('purchase_order');
    }
}
