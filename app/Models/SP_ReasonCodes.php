<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SP_ReasonCodes extends Model
{
    use SoftDeletes;

    protected $connection   = 'digismart';
    protected $table        = 'SP_ReasonCodes';
    protected $primaryKey   = 'reason_code';
    public    $incrementing = false;
    protected $fillable     = ['reason_code', 'description'];

    public static function getReasonList()
    {
        return self::select('reason_code', 'description')
            ->orderBy('description')
            ->get();
    }
}
