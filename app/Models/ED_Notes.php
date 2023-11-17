<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ED_Notes extends Model
{
    public    $incrementing = false;
    protected $connection   = 'digismart';
    protected $table        = 'ED_Notes';
    protected $fillable     = ['date', 'note'];
    protected $casts        = ['date' => 'date'];
    protected $keyType      = 'string';
    protected $primaryKey   = 'date';

    public static function getNotes()
    {
        return self::select('note')
            ->whereDate('date', '=', date('Y-m-d'))
            ->first()
            ->note;
    }
}
