<?php

namespace App\Laravel\Middlewares\Api;

use Closure, Helper;

class ValidResponseFormat
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

        if(!in_array($request->format, ['json','xml'])) {
            return response()->json([
                'status' => FALSE,
                'status_code' => "UNSUPPORTED_RESPONSE_FORMAT",
                'hint' => "Check your api request if it matches with any of the available response formats.",
            ], 415);
        }
        return $next($request);
    }
    
}