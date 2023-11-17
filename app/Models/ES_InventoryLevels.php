<?php

namespace App\Models;

use App\Models\Shopify\InventoryLevels;
use Illuminate\Database\Eloquent\Model;

class ES_InventoryLevels extends Model
{
    public    $incrementing = false;
    protected $connection   = 'digismart';
    protected $table        = 'ES_InventoryLevels';
    protected $fillable     = ['id', 'on_hand'];

    public static function getInventoryLevels()
    {
        return self::get();
    }
}
