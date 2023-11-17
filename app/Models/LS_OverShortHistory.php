<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LS_OverShortHistory extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'LS_OverShortHistory';
    protected $fillable   = ['type'];
}

