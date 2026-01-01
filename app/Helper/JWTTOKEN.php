<?php


namespace App\Helper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class JWTTOKEN{
    public static function CreateToken($email, $userId){
        $payload=[
            'iss' => 'laravel-app',
            'sub' => $userId,
            'email'=> $email,
            'iat' => time(),
            'exp' => time() + 60*60*24 
        ];
        return JWT::encode($payload, env('JWT_KEY'),  'HS256');
    }

    public static function VerifyToken($token)
            {
                try {
                    return JWT::decode($token, new Key(env('JWT_KEY'), 'HS256'));
                } catch (\Exception $e) {
                    return false;
                }
            }

};
