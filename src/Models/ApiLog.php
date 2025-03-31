<?php

namespace CodeTech\ApiLogs\Models;

use Illuminate\Database\Eloquent\Model;

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
     * @inheritdoc
     */
    protected $casts = [
        'request_data' => 'json',
        'request_headers' => 'json',
        'response_data' => 'json',
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
    ];


    public function causer()
    {
        return $this->morphTo();
    }
}
