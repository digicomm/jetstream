<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LS_OverShortStatus extends Model
{

    protected $connection = 'digismart';
    protected $table      = 'LS_OverShortStatus';
    protected $fillable   = ['status'];

    public static function getStatusList()
    {
        return self::select('id', 'status')->orderBy('id')->get();
    }

    public static function getStatus($id)
    {
        return self::select('status')
            ->where('id', '=', $id)
            ->first();
    }
}

