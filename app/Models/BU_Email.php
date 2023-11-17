<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BU_Email extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'BU_Email';
    protected $fillable   = ['name', 'email'];
}
