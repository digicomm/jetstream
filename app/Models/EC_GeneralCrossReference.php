<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EC_GeneralCrossReference extends Model
{
    public    $incrementing = false;
    protected $connection   = 'digismart';
    protected $table        = 'EC_GeneralCrossReference';
    protected $fillable     = ['CrossReferenceList', 'MASValue', 'CrossReferenceCode', 'Description'];
}
