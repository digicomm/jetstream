<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LS_QUAStatus extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'LS_QUAStatus';

    public static function listStatuses()
    {
        return self::orderBy('id')
            ->get();
    }

    public static function getStatus($id)
    {
        return self::where('id', $id)->first();
    }

}
