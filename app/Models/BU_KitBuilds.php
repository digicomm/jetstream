<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BU_KitBuilds extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'BU_KitBuilds';
    protected $fillable   = ['kit', 'quantity'];

}
