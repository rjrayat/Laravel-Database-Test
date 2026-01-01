<?php

namespace App\Http\Middleware;

use App\Helper\JWTTOKEN;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Token Check
        $token = $request -> header('Authorization');
        if (!$token){
            return response() -> json([
                'status' => false,
                'message' => 'Token missing'
            ], 401);
        }

        // Bearer Remove
        $token = str_replace('Bearer', '', $token);

        // Token Verify
        $decoded = JWTTOKEN::VerifyToken($token);
        if($decoded === false){
            return response()->json([
                'status' => false,
                'message'=>"Invalid or expired token"
            ], 401);
        }

        //Request user info attach

        $request -> merge([
            'user_id' => $decoded -> sub,
            'user_email'=> $decoded -> email,
        ]);

        return $next($request);


    }
}
