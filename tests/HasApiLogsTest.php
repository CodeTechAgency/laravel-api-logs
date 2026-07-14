<?php

namespace CodeTech\ApiLogs\Tests;

use CodeTech\ApiLogs\Models\ApiLog;
use CodeTech\ApiLogs\Tests\Fixtures\User;

class HasApiLogsTest extends TestCase
{
    public function test_api_logs_morph_relation_round_trips(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => 'secret',
        ]);

        $log = $user->apiLogs()->create([
            'duration' => 0.123,
            'url' => 'https://example.com/api/ping',
            'method' => 'GET',
            'ip' => '127.0.0.1',
            'request_data' => ['foo' => 'bar'],
            'request_headers' => ['accept' => ['application/json']],
            'response_data' => ['pong' => true],
        ]);

        $this->assertSame(1, $user->apiLogs()->count());
        $this->assertTrue(ApiLog::first()->causer->is($user));
        $this->assertSame(['foo' => 'bar'], $log->fresh()->request_data);
    }
}
