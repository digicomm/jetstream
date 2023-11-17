<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CN_LogChecked extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'CN_LogChecked';
    protected $fillable   = ['invoice_number', 'product_code', 'quantity', 'user'];

}
