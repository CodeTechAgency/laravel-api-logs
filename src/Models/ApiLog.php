<?php

namespace CodeTech\ApiLogs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ApiLog extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'duration',
        'url',
        'method',
        'ip',
        'request_data',
        'request_headers',
        'response_data',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'request_data' => 'json',
            'request_headers' => 'json',
            'response_data' => 'json',
            'created_at' => 'datetime:d/m/Y H:i',
            'updated_at' => 'datetime:d/m/Y H:i',
        ];
    }

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }
}
