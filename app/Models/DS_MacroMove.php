<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DS_MacroMove extends Model
{
    public    $incrementing = false;
    protected $connection   = 'digismart';
    protected $table        = 'DS_MacroMove';

}
