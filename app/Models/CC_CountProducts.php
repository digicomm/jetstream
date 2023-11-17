<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CC_CountProducts extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'CC_CountProducts';
    protected $fillable   = ['header_id', 'product_code'];

}
