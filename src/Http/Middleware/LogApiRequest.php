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

        $content = $response->getContent();
        $responseData = is_string($content) ? json_decode($content, true) : null;

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
            'response_data' => is_array($responseData) ? Redactor::redact($responseData, $keys, $replacement) : $responseData,
        ]);
    }
}
