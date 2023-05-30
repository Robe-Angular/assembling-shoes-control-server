<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{

        /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if($guard != null)
             try {
                if($guard=='user'){
                    $user=auth('user')->user();
                }
                if($guard == 'admin'){
                    $user=auth('admin')->user();
                }
                    
                JWTAuth::parseToken()->authenticate();
                auth()->shouldUse($guard);
                if (!$user) {
                    throw new Exception('Invalid JWT subject');
                }
            } catch (\Throwable $e) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
        return $next($request);
    }
}