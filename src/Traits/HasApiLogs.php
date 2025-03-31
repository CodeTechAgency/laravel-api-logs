<?php

namespace CodeTech\ApiLogs\Traits;

use CodeTech\ApiLogs\Models\ApiLog;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasApiLogs
{
    public function apiLogs(): MorphMany
    {
        return $this->morphMany(ApiLog::class, 'causer');
    }
}
