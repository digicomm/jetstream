<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AP_Vendor extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'AP_Vendor';
    protected $fillable   = ['VendorNo', 'VendorName', 'AddressLine1', 'AddressLine2', 'AddressLine3', 'City', 'State', 'ZipCode', 'CountryCode'];
    protected $keyType    = 'string';
    protected $primaryKey = 'VendorNo';

    public static function getVendorList()
    {
        return self::select('VendorNo')
            ->selectRaw('CONCAT(VendorName, \' (\', VendorNo, \')\') AS VendorName')
            ->where('VendorName', '<>', '')
            ->orderBy('VendorName')
            ->get();
    }
}
