<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAS_AR_Customer extends Model
{
    protected $connection   = 'mars';
    protected $table        = 'AR_Customer';
    protected $primaryKey   = 'CustomerNo';
    public    $incrementing = false;
    protected $keyType      = 'string';

    public static function getCustomerList()
    {
        return self::select('CustomerNo', 'CustomerName')
            ->orderBy('CustomerNo')
            ->get();
    }

    public static function validateCustomerNo($customer_no)
    {
        if ($customer_no) {
            return self::select('CustomerNo')
                ->where('CustomerNo', '=', $customer_no)
                ->exists();
        } else {
            return true;
        }

    }
}
