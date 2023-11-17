<?php

namespace App\Models;

use App\Casts\FifoDate;
use Illuminate\Database\Eloquent\Model;

class SP_LastTransaction extends Model
{
    protected $connection   = 'digismart';
    protected $table        = 'SP_LastTransaction';
    protected $primaryKey   = 'product_code';
    protected $keyType      = 'string';
    public    $incrementing = false;
    protected $casts        = [
        'last_shipment' => FifoDate::class,
        'last_adjustment' => FifoDate::class
    ];

    protected $fillable = ['product_code', 'last_shipment', 'last_adjustment'];
}
