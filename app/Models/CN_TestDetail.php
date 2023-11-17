<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CN_TestDetail extends Model
{
    use SoftDeletes;

    protected $connection = 'digismart';
    protected $table      = 'CN_TestDetail';
    protected $fillable   = ['invoice_number', 'test_id', 'product_code', 'quantity', 'initials', 'test_date'];


}
