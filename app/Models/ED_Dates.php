<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ED_Dates extends Model
{
    public    $incrementing = false;
    protected $connection   = 'digismart';
    protected $table        = 'ED_Dates';
    protected $fillable     = ['date', 'created_at'];
    protected $casts        = ['date' => 'date'];
    protected $keyType      = 'string';
    protected $primaryKey   = 'date';

    public function getNote()
    {
        return $this->hasOne(ED_Notes::class, 'date', 'date');
    }

    public function getPickSheets()
    {
        return $this->hasMany(ED_PickSheets::class, 'date', 'date')
            ->where('date', '=', date('Y-m-d'));
    }
}
