<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BU_Kits extends Model
{
    public    $incrementing = false;
    protected $connection   = 'digismart';
    protected $table        = 'BU_Kits';
    protected $keyType      = 'string';


    public function kitComponents()
    {
        return $this->hasMany(BU_KitComponents::class, 'kit');
    }
}
