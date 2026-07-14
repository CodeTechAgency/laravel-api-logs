<?php

namespace CodeTech\ApiLogs\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        $user = auth()->user();

        if ($user === null || ! method_exists($user, 'apiLogs')) {
            return;
        }

        $start = $request->attributes->get('api_logs.start')
            ?? $request->server('REQUEST_TIME_FLOAT')
            ?? microtime(true);

        $content = $response->getContent();

        $user->apiLogs()->create([
            'duration' => microtime(true) - $start,
            'url' => $request->fullUrl(),
            'method' => $request->getMethod(),
            'ip' => $request->getClientIp(),
            'request_data' => $request->all(),
            'request_headers' => $request->headers->all(),
            'response_data' => is_string($content) ? json_decode($content) : null,
        ]);
    }
}
