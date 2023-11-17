<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CN_Tracking extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'CN_Tracking';
    protected $fillable   = ['invoice_number', 'master_tracking_number', 'tracking_number', 'status'];
}
