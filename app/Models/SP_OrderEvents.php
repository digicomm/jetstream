<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SP_OrderEvents extends Model
{
    protected $connection   = 'digismart';
    protected $table        = 'SP_OrderEvents';
    protected $fillable     = ['date_time', 'event', 'order_number', 'user_id', 'user_name'];
    public    $incrementing = false;

}
