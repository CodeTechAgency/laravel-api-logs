<?php

namespace CodeTech\ApiLogs\Http\Middleware;

use Closure;
use CodeTech\ApiLogs\Models\ApiLog;

class LogApiRequest
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->start = microtime(true);

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param $request
     * @param $response
     */
    public function terminate($request, $response)
    {
        $request->end = microtime(true);

        $data = [
            'duration' => $request->end - $request->start,
            'url' => $request->fullUrl(),
            'method' => $request->getMethod(),
            'ip' => $request->getClientIp(),
            'request_data' => $request->all(),
            'response_data' => json_decode($response->getContent()),
            'user_id' => auth()->check() ? auth()->id() : null,
        ];

        ApiLog::create($data);
    }
}
