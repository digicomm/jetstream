<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAS_GL_CompanyActiveBatch extends Model
{
    protected $connection   = 'mars_write';
    protected $table        = 'GL_CompanyActiveBatch';
    protected $primaryKey   = 'BatchNo';
    public    $incrementing = false;
    protected $keyType      = 'string';
    public    $timestamps   = false;

    public static function getBatches()
    {
        return self::select('BatchNo', 'Comment', 'UserCreatedKey', 'SY_User.UserLogon')
            ->leftJoin('SY_User', 'GL_CompanyActiveBatch.UserCreatedKey', '=', 'SY_User.UserKey')
            ->where('BatchNo', '!=', '00000')
            ->where('ModuleCode', 'I/M')
            ->where('Comment', 'like', 'VI Import: %')
            ->get();
    }

    public static function getComment($fixedFileName)
    {
        return self::select('BatchNo', 'Comment')->where('Comment', $fixedFileName)->first();
    }

    public static function getUploaded()
    {
        return self::select('BatchNo', 'Comment')
            ->where('ModuleCode', 'I/M')
            ->where('UserCreatedKey', '!=', '0000000204')
            ->where('Comment', '<>', '')
            ->where('BatchNo', '!=', '00000')
            ->orderBy('Comment', 'ASC')
            ->get();
    }

    public function getUsername()
    {
        return self::hasOne(MAS_SY_User::class, 'UserKey', 'UserCreatedKey');
    }
}
