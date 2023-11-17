<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LS_Cities extends Model
{
    protected $connection   = 'digismart';
    protected $table        = 'LS_Cities';
    protected $primaryKey   = 'city';
    protected $keyType      = 'string';
    protected $fillable     = ['city'];
    public    $incrementing = false;

    public static function autocompleteCity($request)
    {
        return self::selectRaw('upper(city) as value, upper(city) as label')
            ->where('city', 'like', $request->term . '%')
            ->limit(10)
            ->get();
    }
}
