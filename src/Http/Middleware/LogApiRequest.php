<?php

namespace CodeTech\ApiLogs\Http\Middleware;

use Closure;
use CodeTech\ApiLogs\Support\Redactor;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequest
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->set('api_logs.start', microtime(true));

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        $user = auth()->guard(config('api-logs.guard'))->user();

        if ($user === null || ! method_exists($user, 'apiLogs')) {
            return;
        }

        $start = $request->attributes->get('api_logs.start')
            ?? $request->server('REQUEST_TIME_FLOAT')
            ?? microtime(true);

        $keys = config('api-logs.redact.keys', []);
        $headers = config('api-logs.redact.headers', []);
        $replacement = config('api-logs.redact.replacement', '[REDACTED]');

        $query = Redactor::redact($request->query(), $keys, $replacement);

        $user->apiLogs()->create([
            'duration' => microtime(true) - $start,
            'url' => $query === [] ? $request->url() : $request->url().'?'.Arr::query($query),
            'method' => $request->getMethod(),
            'ip' => $request->getClientIp(),
            'request_data' => Redactor::redact($request->all(), $keys, $replacement),
            'request_headers' => Redactor::redactHeaders($request->headers->all(), $headers, $replacement),
            'response_data' => $this->responseData($response, $keys, $replacement),
        ]);
    }

    /**
     * Get the response body to store: the decoded JSON value when the body
     * is JSON, or the raw body as a UTF-8 string otherwise.
     */
    private function responseData(Response $response, array $keys, string $replacement): mixed
    {
        $content = $response->getContent();

        if (! is_string($content)) {
            return '';
        }

        $decoded = json_decode($content, true);

        if (is_array($decoded)) {
            return Redactor::redact($decoded, $keys, $replacement);
        }

        return $decoded ?? mb_scrub($content, 'UTF-8');
    }
}
