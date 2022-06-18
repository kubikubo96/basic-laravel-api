<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\JWTAuth;

class JwtVerifyMiddleware extends BaseMiddleware
{
    public function __construct(JWTAuth $auth)
    {
        parent::__construct($auth);
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $this->auth->parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'status' => 'Token is Invalid',
                    "message" => "INVALID_TOKEN",
                    "timestamp" => now()
                ], 401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json([
                    'status' => 'Token is Expired',
                    "message" => "TOKEN_EXPIRED",
                    "timestamp" => now()
                ], 401);
            } else {
                return response()->json([
                    'status' => 'Authorization Token not found',
                    "message" => "TOKEN_NOT_FOUND",
                    "timestamp" => now()
                ], 401);
            }
        }
        return $next($request);
    }
}
