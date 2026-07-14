<?php

namespace CodeTech\ApiLogs\Http\Middleware;

use Closure;
use CodeTech\ApiLogs\Support\Redactor;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LogApiRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->start = microtime(true);

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param  Request  $request
     * @param $response
     * @return void
     */
    public function terminate(Request $request, $response): void
    {
        if (! auth()->check()) {
            return;
        }

        $request->end = microtime(true);

        $content = $response->getContent();
        $responseData = is_string($content) ? json_decode($content, true) : null;

        $keys = config('api-logs.redact.keys', []);
        $headers = config('api-logs.redact.headers', []);
        $replacement = config('api-logs.redact.replacement', '[REDACTED]');

        $query = Redactor::redact($request->query(), $keys, $replacement);

        auth()->user()->apiLogs()->create([
            'duration' => $request->end - $request->start,
            'url' => $query === [] ? $request->url() : $request->url().'?'.Arr::query($query),
            'method' => $request->getMethod(),
            'ip' => $request->getClientIp(),
            'request_data' => Redactor::redact($request->all(), $keys, $replacement),
            'request_headers' => Redactor::redactHeaders($request->headers->all(), $headers, $replacement),
            'response_data' => is_array($responseData) ? Redactor::redact($responseData, $keys, $replacement) : $responseData,
        ]);
    }
}
