<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BU_Priority extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'BU_Priority';

    public static function listPriorities()
    {
        return self::orderBy('id')
            ->get();
    }

    public static function getPriority($id)
    {
        return self::where('id', $id)->first();
    }
}
