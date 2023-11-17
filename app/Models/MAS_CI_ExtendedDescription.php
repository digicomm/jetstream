<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAS_CI_ExtendedDescription extends Model
{
    public    $incrementing = false;
    protected $connection   = 'mars';
    protected $table        = 'CI_ExtendedDescription';
    protected $primaryKey   = 'ExtendedDescriptionKey';
    protected $keyType      = 'string';

    public static function getDescriptionCache()
    {
        return self::select('ExtendedDescriptionKey', 'ExtendedDescriptionText', 'DateCreated', 'DateUpdated')
            ->orderBy('ExtendedDescriptionKey')
            ->get();
    }
}
