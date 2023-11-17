<?php

namespace App\Models;

use App\Models\Shopify\Metafields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ES_Metafields extends Model
{
    use SoftDeletes;

    protected $connection = 'digismart';
    protected $table      = 'ES_Metafields';
    protected $fillable   = ['id', 'namespace', 'key', 'value', 'value_type', 'owner_id'];

    public static function getSageInfo($product_id)
    {
        return self::select('key', 'value')
            ->where('product_id', $product_id)->get();
    }
}
