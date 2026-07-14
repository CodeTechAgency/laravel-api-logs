<?php

namespace CodeTech\ApiLogs\Tests\Unit;

use CodeTech\ApiLogs\Support\Redactor;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class RedactorTest extends PHPUnitTestCase
{
    public function test_redacts_matching_keys_recursively(): void
    {
        $data = [
            'email' => 'user@example.com',
            'password' => 'secret123',
            'profile' => [
                'name' => 'Test',
                'credentials' => [
                    'api_token' => 'abc123',
                ],
            ],
        ];

        $result = Redactor::redact($data, ['password', 'api_token'], '[REDACTED]');

        $this->assertSame('user@example.com', $result['email']);
        $this->assertSame('[REDACTED]', $result['password']);
        $this->assertSame('Test', $result['profile']['name']);
        $this->assertSame('[REDACTED]', $result['profile']['credentials']['api_token']);
    }

    public function test_matches_keys_case_insensitively(): void
    {
        $result = Redactor::redact(
            ['Authorization' => 'Bearer abc', 'X-API-KEY' => ['zzz']],
            ['authorization', 'x-api-key'],
            '[REDACTED]'
        );

        $this->assertSame('[REDACTED]', $result['Authorization']);
        $this->assertSame('[REDACTED]', $result['X-API-KEY']);
    }

    public function test_replaces_array_values_entirely(): void
    {
        $result = Redactor::redact(
            ['credit_card' => ['number' => '4111', 'cvv' => '123'], 'items' => [1, 2]],
            ['credit_card'],
            '[REDACTED]'
        );

        $this->assertSame('[REDACTED]', $result['credit_card']);
        $this->assertSame([1, 2], $result['items']);
    }

    public function test_redact_headers_preserves_value_shape(): void
    {
        $result = Redactor::redactHeaders(
            ['Cookie' => ['session=1', 'other=2'], 'accept' => ['application/json']],
            ['cookie'],
            '[REDACTED]'
        );

        $this->assertSame(['[REDACTED]'], $result['Cookie']);
        $this->assertSame(['application/json'], $result['accept']);
    }

    public function test_handles_non_string_keys(): void
    {
        $result = Redactor::redact(
            ['password' => 'secret', 0 => 'zero', 1 => ['token' => 'abc']],
            ['password', 123, 'token'],
            '[REDACTED]'
        );

        $this->assertSame('[REDACTED]', $result['password']);
        $this->assertSame('zero', $result[0]);
        $this->assertSame('[REDACTED]', $result[1]['token']);
    }

    public function test_leaves_data_untouched_when_nothing_matches(): void
    {
        $data = ['foo' => 'bar', 'nested' => ['baz' => 1]];

        $this->assertSame($data, Redactor::redact($data, ['password'], '[REDACTED]'));
    }
}
