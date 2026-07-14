<?php

namespace CodeTech\ApiLogs\Tests;

use CodeTech\ApiLogs\Http\Middleware\LogApiRequest;
use CodeTech\ApiLogs\Models\ApiLog;
use CodeTech\ApiLogs\Tests\Fixtures\User;

class LogApiRequestMiddlewareTest extends TestCase
{
    protected function defineRoutes($router): void
    {
        $router->middleware(LogApiRequest::class)->group(function () use ($router) {
            $router->get('/api/ping', fn () => response()->json(['pong' => true]));
            $router->post('/api/echo', fn () => response()->json(['ok' => true]));
        });
    }

    public function test_authenticated_request_is_logged(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => 'secret',
        ]);

        $this->actingAs($user)
            ->postJson('/api/echo?foo=bar', ['name' => 'value'])
            ->assertOk();

        $this->assertSame(1, ApiLog::count());

        $log = ApiLog::first();
        $this->assertSame('POST', $log->method);
        $this->assertStringContainsString('/api/echo', $log->url);
        $this->assertNotEmpty($log->ip);
        $this->assertGreaterThan(0, $log->duration);
        $this->assertEquals(['foo' => 'bar', 'name' => 'value'], $log->request_data);
        $this->assertSame(['ok' => true], (array) $log->response_data);
        $this->assertArrayHasKey('host', $log->request_headers);
        $this->assertTrue($log->causer->is($user));
    }

    public function test_unauthenticated_request_passes_through_and_is_not_logged(): void
    {
        $this->getJson('/api/ping')
            ->assertOk()
            ->assertJson(['pong' => true]);

        $this->assertSame(0, ApiLog::count());
    }
}
