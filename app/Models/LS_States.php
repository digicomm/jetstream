<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LS_States extends Model
{
    protected $connection   = 'digismart';
    protected $table        = 'LS_States';
    protected $primaryKey   = 'code';
    protected $keyType      = 'string';
    protected $fillable     = ['code', 'state'];
    public    $incrementing = false;

    public static function listStates()
    {
        return self::select('code')->selectRaw('upper(state) as state')->get();
    }
}
