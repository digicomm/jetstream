<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAS_SY_User extends Model
{
    protected $connection   = 'mars';
    protected $table        = 'SY_User';
    protected $primaryKey   = 'UserKey';
    protected $keyType      = 'string';
    public    $incrementing = false;

    public static function getUserCache()
    {
        return self::selectRaw('UserKey as user_key,UserLogon as user_logon,Active as active')
            ->orderBy('UserKey')
            ->get();
    }
}

