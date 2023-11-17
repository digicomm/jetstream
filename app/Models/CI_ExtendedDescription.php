<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CI_ExtendedDescription extends Model
{
    public    $incrementing = false;
    protected $connection   = 'digismart';
    protected $table        = 'CI_ExtendedDescription';
    protected $primaryKey   = 'ExtendedDescriptionKey';
    protected $fillable     = ['ExtendedDescriptionKey', 'ExtendedDescriptionText', 'DateCreated', 'DateUpdated'];
}
