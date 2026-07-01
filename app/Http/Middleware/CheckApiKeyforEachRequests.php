<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKeyforEachRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apikey = $request->header('X-API-KEY');
        if (!$apikey || $apikey !== config('services.apikey.key')){
            return response()->json(["message" => "API KEY invalid"], 422);
        }

        
        return $next($request);
    }
}
