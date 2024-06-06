<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        if ($request->getMethod() === "OPTIONS"){
          //The client-side application can set only headers allowed in Access-Control-Allow-Headers
          return response('')
          ->header('Access-Control-Allow-Origin', '*')
          ->header('Access-Control-Allow-Methods', '*')
          ->header('Access-Control-Allow-Headers', ['Authorization', 'Content-Type']);
        }
        
        $handle = $next($request);
        
        if( !method_exists($handle, 'header') ){
            return $handle;
        }
        
        return $handle->header('Access-Control-Allow-Origin', '*')
               ->header('Access-Control-Allow-Methods', '*')
               ->header('Access-Control-Allow-Headers', ['Authorization', 'Content-Type']);
        
    }
}
