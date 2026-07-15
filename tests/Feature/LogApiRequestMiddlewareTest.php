<?php

namespace CodeTech\ApiLogs\Tests\Feature;

use CodeTech\ApiLogs\Http\Middleware\LogApiRequest;
use CodeTech\ApiLogs\Models\ApiLog;
use CodeTech\ApiLogs\Tests\Fixtures\User;
use CodeTech\ApiLogs\Tests\TestCase;

class LogApiRequestMiddlewareTest extends TestCase
{
    protected function defineRoutes($router): void
    {
        $router->middleware(LogApiRequest::class)->group(function () use ($router) {
            $router->get('/api/ping', fn () => response()->json(['pong' => true]));
            $router->post('/api/echo', fn () => response()->json(['ok' => true]));
            $router->post('/api/login', fn () => response()->json([
                'user' => ['email' => 'user@example.com'],
                'access_token' => 'issued-token',
            ]));
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

    public function test_sensitive_fields_are_redacted(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => 'secret',
        ]);

        $this->actingAs($user)
            ->postJson('/api/login?api_token=query-token', [
                'email' => 'user@example.com',
                'password' => 'super-secret',
                'meta' => ['refresh_token' => 'nested-token'],
            ], ['Authorization' => 'Bearer abc123'])
            ->assertOk();

        $log = ApiLog::first();

        $this->assertSame('[REDACTED]', $log->request_data['password']);
        $this->assertSame('[REDACTED]', $log->request_data['meta']['refresh_token']);
        $this->assertSame('user@example.com', $log->request_data['email']);

        $this->assertSame(['[REDACTED]'], $log->request_headers['authorization']);
        $this->assertArrayHasKey('host', $log->request_headers);

        $this->assertSame('[REDACTED]', $log->response_data['access_token']);
        $this->assertSame('user@example.com', $log->response_data['user']['email']);

        $this->assertStringContainsString('api_token='.urlencode('[REDACTED]'), $log->url);
        $this->assertStringNotContainsString('query-token', $log->url);
    }

    public function test_redaction_lists_are_configurable(): void
    {
        config()->set('api-logs.redact.keys', ['ssn']);
        config()->set('api-logs.redact.replacement', '***');

        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => 'secret',
        ]);

        $this->actingAs($user)
            ->postJson('/api/echo', ['ssn' => '123-45-6789', 'password' => 'kept-now'])
            ->assertOk();

        $log = ApiLog::first();

        $this->assertSame('***', $log->request_data['ssn']);
        $this->assertSame('kept-now', $log->request_data['password']);
    }

    public function test_configured_guard_is_used_to_resolve_the_user(): void
    {
        $this->defineApiGuard();
        config()->set('api-logs.guard', 'api');

        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => 'secret',
        ]);

        $this->actingAs($user, 'api')
            ->postJson('/api/echo', ['name' => 'value'])
            ->assertOk();

        $this->assertSame(1, ApiLog::count());
        $this->assertTrue(ApiLog::first()->causer->is($user));
    }

    public function test_request_is_not_logged_when_configured_guard_is_unauthenticated(): void
    {
        $this->defineApiGuard();
        config()->set('api-logs.guard', 'api');

        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => 'secret',
        ]);

        $this->actingAs($user, 'web')
            ->postJson('/api/echo', ['name' => 'value'])
            ->assertOk();

        $this->assertSame(0, ApiLog::count());
    }

    private function defineApiGuard(): void
    {
        config()->set('auth.guards.api', ['driver' => 'session', 'provider' => 'users']);
        config()->set('auth.providers.users', ['driver' => 'eloquent', 'model' => User::class]);
    }

    public function test_unauthenticated_request_passes_through_and_is_not_logged(): void
    {
        $this->getJson('/api/ping')
            ->assertOk()
            ->assertJson(['pong' => true]);

        $this->assertSame(0, ApiLog::count());
    }
}
