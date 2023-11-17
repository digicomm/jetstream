<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BU_Notes extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'BU_Notes';
    protected $fillable   = ['name', 'email'];


    public static function getNotes($id)
    {
        return self::select('BU_Notes.*', 'LA_Users.name')
            ->where("build_id", $id)
            ->where("type", "Note")
            ->join('LA_Users', 'BU_Notes.user_id', '=', 'LA_Users.id')
            ->orderBy("created_at", "desc")->get();
    }

    public static function getStatuses($id)
    {
        return self::select('BU_Notes.*', 'LA_Users.name')
            ->where("build_id", $id)
            ->where("type", "!=", "Note")
            ->join('LA_Users', 'BU_Notes.user_id', '=', 'LA_Users.id')
            ->orderBy("created_at", "desc")->get();
    }

    public static function getNotesAndStatuses($id)
    {
        return self::select('BU_Notes.*', 'LA_Users.name')
            ->where("build_id", $id)
            ->join('LA_Users', 'BU_Notes.user_id', '=', 'LA_Users.id')
            ->orderBy("created_at", "desc")->get();
    }
}
