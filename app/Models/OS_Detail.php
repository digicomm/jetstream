<?php

namespace App\Models;

use App\Models\OverShort\Issue;
use Illuminate\Database\Eloquent\Model;

class OS_Detail extends Model
{
    protected $connection = 'digismart';
    protected $table      = 'OS_Detail';
    protected $fillable   = ['issue_id', 'product_code', 'over', 'short', 'damaged', 'note'];
    protected $touches    = ['issue'];

    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }
}

