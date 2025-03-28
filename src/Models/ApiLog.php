<?php

namespace CodeTech\ApiLogs\Models;

use App\Models\User;
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
        'user_id',
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

    /**
     * Get the user that owns this session log.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
