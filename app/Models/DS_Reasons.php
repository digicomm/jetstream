<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DS_Reasons extends Model
{
    public    $incrementing = false;
    protected $connection   = 'digismart';
    protected $table        = 'DS_Reasons';
    protected $fillable     = ['code', 'reason'];
    protected $primaryKey   = 'code';
}
