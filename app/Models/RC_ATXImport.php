<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RC_ATXImport extends Model
{
    protected $connection   = 'digismart';
    protected $table        = 'RC_ATXImport';
    protected $fillable     = ['shipment', 'filename', 'packing_slip', 'line'];
    public    $incrementing = false;
    protected $primaryKey   = 'shipment';

    public function getGroupedParts()
    {
        return $this->hasMany(RC_ATXPack::class, 'shipment', 'shipment')
            ->select('product_code', 'purchase_order', 'shipment')
            ->selectRaw('sum(quantity_shipped) as quantity_shipped')
            ->groupBy('product_code', 'purchase_order', 'shipment')
            ->orderBy('product_code')
            ->orderBy('purchase_order');
    }
}
