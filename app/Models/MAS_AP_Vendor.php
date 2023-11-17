<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAS_AP_Vendor extends Model
{
    protected $connection   = 'mars';
    protected $table        = 'AP_Vendor';
    protected $primaryKey   = 'VendorNo';
    public    $incrementing = false;
    protected $keyType      = 'string';

    public static function getVendorList()
    {
        return self::select('VendorNo', 'VendorName', 'AddressLine1', 'AddressLine2', 'AddressLine3', 'City', 'State', 'ZipCode', 'CountryCode')
            ->orderBy('VendorNo')
            ->get();
    }
}
