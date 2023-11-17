<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DS_BackorderExcludeCustomer extends Model
{
    use SoftDeletes;

    public    $incrementing = false;
    protected $connection   = 'digismart';
    protected $table        = 'DS_BackorderExcludeCustomer';
    protected $primaryKey   = 'customer_no';
    protected $keyType      = 'string';
    protected $fillable     = ['customer_no'];
}
