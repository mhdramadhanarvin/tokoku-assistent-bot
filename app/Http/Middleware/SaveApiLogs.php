<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiLogModel;

class SaveApiLogs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $requestData = [
            'url' => $request->fullUrl(),
            'input' => $request->all(),
            'ip'        => $request->ip()
        ];
        $response = $next($request);
        $responseData = [
            'status'    => $response->status(),
            'content'   => $response->content()
        ];

        ApiLogModel::create([
            'request'   => json_encode($requestData),
            'response'  => json_encode($responseData)
        ]);

        return $response;
    }
}
