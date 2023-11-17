<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CN_LogCartons extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'CN_LogCartons';
    protected $fillable   = ['invoice_number', 'carton', 'user', 'action'];

}
