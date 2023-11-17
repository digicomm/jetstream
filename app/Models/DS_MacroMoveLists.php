<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DS_MacroMoveLists extends Model
{
    public    $incrementing = false;
    protected $connection   = 'digismart';
    protected $table        = 'DS_MacroMoveLists';
    protected $primaryKey   = 'list';
    protected $keyType      = 'string';

    public function getListItems()
    {
        return $this->hasMany(DS_MacroMove::class, 'list', 'list');
    }
}
