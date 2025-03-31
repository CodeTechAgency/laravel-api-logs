<?php

namespace CodeTech\ApiLogs\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
        $request->end = microtime(true);

        auth()->user()->apiLogs()->create([
            'duration' => $request->end - $request->start,
            'url' => $request->fullUrl(),
            'method' => $request->getMethod(),
            'ip' => $request->getClientIp(),
            'request_data' => $request->all(),
            'request_headers' => $request->headers->all(),
            'response_data' => json_decode($response->getContent()),
        ]);
    }
}
