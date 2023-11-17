<?php

namespace App\Models;

use App\Models\Shopify\Variants;
use Illuminate\Database\Eloquent\Model;

class ES_Variants extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'ES_Variants';
    protected $fillable   = ['id', 'product_id', 'inventory_policy', 'grams', 'position', 'price', 'taxable', 'title', 'weight_unit', 'sku', 'weight', 'inventory_item_id'];

}
