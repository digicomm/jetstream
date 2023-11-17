<?php

namespace App\Models;

use App\Models\OverShort\Issue;
use Illuminate\Database\Eloquent\Model;

class OS_Files extends Model
{

    protected $connection = 'digismart';
    protected $table      = 'OS_Files';
    protected $fillable   = ['issue_id', 'filename', 'extension'];
    protected $touches    = ['issue'];

    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }
}

