<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CN_Cartons extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'CN_Cartons';
    protected $fillable   = ['invoice_number', 'carton', 'received', 'note'];
    protected $casts      = ['received' => 'boolean'];
}
