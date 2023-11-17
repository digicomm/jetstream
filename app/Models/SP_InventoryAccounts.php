<?php

namespace App\Models;

use App\Models\v2\SPInventoryAccounts;
use Illuminate\Database\Eloquent\Model;

class SP_InventoryAccounts extends Model
{
    protected $connection   = 'digismart';
    protected $table        = 'SP_InventoryAccounts';
    protected $primaryKey   = 'inventory_account';
    public    $incrementing = false;
    protected $fillable     = ['inventory_account', 'warehouse_code'];

    public static function getInvAcct($warehouse_code)
    {
        return self::select('inventory_account')
            ->where('warehouse_code', '=', $warehouse_code)
            ->first();
    }

    public static function getWarehouse($invAcct)
    {
        return self::select('warehouse')->where('code', '=', $invAcct)->first();
    }

    public static function getSorted()
    {
        $warehouses = self::select('code', 'warehouse')->orderBy('warehouse')->get();
        foreach ($warehouses as $whs) {
            $data[$whs->code] = $whs->warehouse;
        }
        return $data;
    }

    public static function getAutoComplete()
    {
        return self::selectRaw('warehouse as value, warehouse as label')
            ->where('warehouse', 'like', request()->term . '%')
            ->limit(10)
            ->get();
    }

    public static function getWarehouses()
    {
        return SPInventoryAccounts::select('code', 'warehouse')->orderBy('warehouse')->get();
    }

    public static function getLicensePlateWarehouses()
    {
        return SPInventoryAccounts::selectRaw('warehouse as code, warehouse as label')->orderBy('warehouse')->get();
    }

    public static function autocompleteWarehouse($request)
    {
        return self::selectRaw('warehouse as value, warehouse as label')
            ->where('warehouse', 'like', $request->term . '%')
            ->limit(10)
            ->get();
    }

    public static function getWarehouseList()
    {
        return self::selectRaw('CASE WHEN LENGTH(warehouse_code) > 3 THEN UPPER(SUBSTRING(warehouse_code,1,3)) ELSE UPPER(warehouse_code) END AS warehouse_code')
            ->orderBy('warehouse_code')
            ->get();
    }


}
