<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CC_Settings extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'CC_Settings';
    protected $fillable   = ['year', 'end_date', 'holiday1', 'holiday2', 'holiday3', 'holiday4', 'holiday5', 'holiday6', 'holiday7', 'holiday8', 'holiday9', 'holiday10'];
    protected $primaryKey = 'year';


}
