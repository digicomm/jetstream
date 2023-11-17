<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OS_History extends Model
{

    protected $connection = 'digismart';
    protected $table      = 'OS_History';
    protected $fillable   = ['user_id', 'issue_id', 'field_name', 'old_value', 'new_value'];
}

