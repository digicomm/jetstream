<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RP_Emails extends Model
{
    protected $connection = 'digismart';
    protected $table      = "RP_Emails";

    public static function getEmails($report)
    {
        return self::select('email')->where('report', $report)->get();
    }

}
