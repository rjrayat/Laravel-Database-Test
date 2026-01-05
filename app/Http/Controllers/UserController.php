<?php

namespace App\Http\Controllers;

use App\Helper\JWTTOKEN;
use App\Models\BlackListedToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function UserLogin (Request $request){
            //validate
            $request->validate([
                'email' => 'required|email',
                 'password' => 'required'
            ]);

             // Step 2: user check
            $user = User::where('email', $request ->email)->first();
            if(!$user){
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 404);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid password'
                ], 401);
            }
             // Step 4: generate JWT
             $token = JWTTOKEN::CreateToken($user->email, $user->id);

              // Step 5: response
            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'token' => $token
            ], 200);
    }


    public function Profile(Request $request)
        {
            $token = $request->header('Authorization');

            if (!$token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token missing'
                ], 401);
            }

            $token = str_replace('Bearer ', '', $token);

            $decoded = JWTTOKEN::VerifyToken($token);

            if ($decoded === false) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid token'
                ], 401);
            }

            // return response()->json([
            //     'status' => true,
            //     'user_id' => $request->user_id,
            //     'email' => $request->user_email
            // ]);

            return response()->json([
                'status' => true,
                'user' => $request->get('auth_user')
            ]);
        }

    public function Logout(Request $request){
        $token = trim(str_replace('Bearer', '', $request->header('Authorization')));

        // token expire time বের করা
        $decoded= JWTTOKEN::VerifyToken($token);

        BlackListedToken::create([
            'token' =>  $token,
            'expires_at' => date('Y-m-d H:i:s',$decoded->exp)
        ]);
            return response()->json([
                'status' => true,
                'message'=> "Logout successful. Please delete token from client"
            ]);
        }
}
