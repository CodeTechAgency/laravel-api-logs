<?php

namespace CodeTech\ApiLogs\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserWithoutApiLogs extends Authenticatable
{
    protected $table = 'users';

    protected $guarded = [];
}
