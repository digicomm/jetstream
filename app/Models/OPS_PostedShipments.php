<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OPS_PostedShipments extends Model
{
    protected $connection = 'ops';
    protected $table      = 'PostedShipments';
    protected $fillable   = ['BOLPro', 'BackorderCount', 'BinLocation', 'BillToCode', 'CustomerName', 'LineNumber', 'OrderNumber', 'POLine', 'ProductCode', 'PurchaseOrderNumber', 'QuantityAllocated', 'QuantityOrdered', 'QuantityShipped', 'SalesOrderNumber', 'SCAC', 'ShipToCity', 'ShipToCode', 'ShipmentNumber', 'ShipmentDate', 'TagSerialNumber', 'TimePosted', 'WarehouseCode'];
    public    $timestamps = false;
    protected $casts      = [
        'BOLPro' => 'string',
        'BackorderCount',
        'BillToCode' => 'string',
        'BinLocation' => 'string',
        'CustomerName' => 'string',
        'LineNumber' => 'integer',
        'OrderNumber' => 'integer',
        'POLine' => 'string',
        'ProductCode' => 'string',
        'PurchaseOrderNumber' => 'string',
        'QuantityAllocated' => 'float',
        'QuantityOrdered' => 'float',
        'QuantityShipped' => 'float',
        'SalesOrderNumber' => 'string',
        'SCAC' => 'string',
        'ShipToCity' => 'string',
        'ShipToCode' => 'string',
        'ShipmentDate' => 'date',
        'ShipmentNumber' => 'integer',
        'TagSerialNumber' => 'string',
        'TimePosted' => 'datetime',
        'WarehouseCode' => 'string'
    ];

    public static function getLastRecord()
    {
        return self::select('TimePosted')
            ->orderByDesc('TimePosted')
            ->first();
    }
}
