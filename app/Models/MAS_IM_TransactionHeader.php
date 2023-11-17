<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAS_IM_TransactionHeader extends Model
{
    protected $connection = 'mars';
    protected $table      = 'IM_TransactionHeader';
    public    $timestamps = false;

    public static function getComment($batch)
    {
        return self::select('Comment')->where('BatchNo', $batch)->first();
    }


}
