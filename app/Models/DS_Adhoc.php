<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DS_Adhoc extends Model
{
    public    $incrementing = false;
    protected $connection   = 'digismart';
    protected $table        = 'DS_Adhoc';
    protected $primaryKey   = 'report';
    protected $keyType      = 'string';
    protected $fillable     = ['key', 'file_updated'];

    public static function getReportKey($report)
    {
        return self::select('key')->where('report', $report)->first()->key;
    }

    public static function getFileTimes()
    {
        return self::select('report', 'updated_at')->get();
    }

}
