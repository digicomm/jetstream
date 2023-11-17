<?php

namespace App\Models;

use App\Models\Shopify\Fulfillments;
use Illuminate\Database\Eloquent\Model;

class ES_Fulfillments extends Model
{
    public    $incrementing = false;
    protected $connection   = 'digismart';
    protected $table        = 'ES_Fulfillments';
    protected $fillable     = ['order_id', 'tracking', 'zpl', 'carrier'];
    protected $primaryKey   = 'order_id';

}
