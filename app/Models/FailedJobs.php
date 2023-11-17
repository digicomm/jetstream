<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class FailedJobs extends SanctumPersonalAccessToken
{
    protected $connection = 'digismart';
    protected $table      = 'failed_jobs';

}
