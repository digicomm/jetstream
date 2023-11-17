<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BU_Status extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'BU_Status';

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
