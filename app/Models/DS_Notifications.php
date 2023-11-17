<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DS_Notifications extends Model
{
    protected $connection = 'digismart';

    protected $table    = 'DS_Notifications';
    protected $fillable = ['note'];
}
