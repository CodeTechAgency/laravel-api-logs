<?php

namespace CodeTech\ApiLogs\Tests\Fixtures;

use CodeTech\ApiLogs\Traits\HasApiLogs;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiLogs;

    protected $guarded = [];
}
