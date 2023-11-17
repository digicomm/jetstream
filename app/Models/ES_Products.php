<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ES_Products extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'ES_Products';
    protected $fillable   = ['id', 'description_html', 'handle', 'product_type', 'status', 'tags', 'title', 'total_variants', 'tracks_inventory', 'vendor'];

    public static function getProductIds()
    {
        return self::select('id')
            ->orderBy('id')
            ->get();
    }

    public static function deleteOld($valid)
    {
        return self::whereNotIn('id', $valid)->delete();
    }

    public function getVariants()
    {
        return $this->hasMany(ES_Variants::class);
    }

    public function getMetafields()
    {
        return $this->hasOne(ES_Metafields::class);
    }

    public function getSku()
    {
        return $this->hasOne(ES_Variants::class, 'product_id')->first()->sku;
    }
}
