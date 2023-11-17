<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CC_Holidays extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'CC_Holidays';
    protected $fillable   = ['year', 'holiday', 'date'];

}

