<?php

namespace App\Models;

use App\Models\Shopify\Inventory;
use Illuminate\Database\Eloquent\Model;

class ES_Inventory extends Model
{

    protected $connection = 'digismart';
    protected $table      = 'inventory';

    public static function getInventoryLevels()
    {
        return self::get();
    }
}
