<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QA_Notes extends Model
{
    use SoftDeletes;

    protected $connection = 'digismart';
    protected $table      = 'QA_Notes';
    protected $fillable   = ['uid', 'note', 'reason', 'username'];

    public static function getNotes()
    {
        return self::where('uid', request()->uid)->orderBy('id', 'desc')->get();
    }

}
