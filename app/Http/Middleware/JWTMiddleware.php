<?php

namespace App\Http\Middleware;

use App\Helper\JWTTOKEN;
use App\Models\BlackListedToken;
use App\Models\User;
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
        $token = trim(str_replace('Bearer', '', $token));

        // Token Verify
        $decoded = JWTTOKEN::VerifyToken($token);
        if($decoded === false){
            return response()->json([
                'status' => false,
                'message'=>"Invalid or expired token"
            ], 401);
        }

        // //Request user info attach

        // $request -> merge([
        //     'user_id' => $decoded -> sub,
        //     'user_email'=> $decoded -> email,
        // ]);


        $blacklisted= BlackListedToken::where('token', $token)->first();
        if($blacklisted){
            return response()->json([
            'status' => false,
            'message' => 'Token is blacklisted. Please login again.'
        ], 401);
        }

         // 🔥 REAL STEP: Load user from DB
        $user = User::find($decoded->sub);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        // attach full user object
        $request->attributes->set('auth_user', $user);


        return $next($request);


    }
}
