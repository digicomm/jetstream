<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LS_OverShortPriority extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'LS_OverShortPriority';
    protected $fillable   = ['priority'];

    public static function getPriorityList()
    {
        return self::select('id', 'priority')->orderBy('id')->get();
    }

    public static function getPriority($id)
    {
        return self::select('priority')
            ->where('id', '=', $id)
            ->first();
    }
}

