<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SP_BinLocation extends Model
{
    public    $incrementing = false;
    protected $connection   = 'digismart';
    protected $table        = 'SP_BinLocation';
    protected $fillable     = ['bin_location', 'lvl_one_sort', 'lvl_two_sort', 'lvl_three_sort'];
    protected $primaryKey   = 'bin_location';

    public static function getEmptyBins()
    {
        return self::select('bin_location', 'lvl_one_sort', 'lvl_two_sort', 'lvl_three_sort', 'sort_key')
            ->where('empty', '1')
            ->orderBy('bin_location')
            ->get();
    }

    public static function validateBinLocation($bin_location)
    {
        return self::select('bin_location')
            ->where('bin_location', '=', $bin_location)
            ->exists();
    }

    public static function validateChinaBinLocation($bin_location)
    {
        return self::select('bin_location')
            ->where('bin_location', '=', $bin_location)
            ->count();
    }


    public static function autocompleteBinLocation($bin_location)
    {
        return self::selectRaw('bin_location as label, bin_location as value')
            ->where('bin_location', 'like', $bin_location . '%')
            ->limit(25)
            ->orderBy('bin_location')
            ->get();
    }
}