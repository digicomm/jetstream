<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BU_KitBuildsAvailability extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'BU_KitBuildsAvailability';
    protected $fillable   = ['build_id', 'product_code', 'quantity_needed', 'quantity_available'];

}
