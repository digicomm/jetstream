<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LS_QUAType extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'LS_QUAType';

    public static function listTypes()
    {
        return self::orderBy('id')
            ->get();
    }

    public static function getType($id)
    {
        return self::where('id', $id)->first();
    }

}
