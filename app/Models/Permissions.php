<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'permissions';
    public    $incrementing = false;

}
