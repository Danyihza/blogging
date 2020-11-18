<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register (Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = new User;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);

        $jwt = JWT::encode([
            "iat" => date('Y-m-d H:i:s'),
            "data" => $user
        ], env('JWT_SECRET'));

        try {
            $user->save();

            return response()->json([
                'success' => true,
                'data' => [
                    'token' => $jwt
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null
            ], 500);
        }
    }

    public function login (Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('username', $request->username)->first();

        if (Hash::check($request->password, $user->password)) {
            $jwt = JWT::encode([
                "iat" => date('Y-m-d H:i:s'),
                "data" => $user
            ], env('JWT_SECRET'));

            return response()->json([
                'success' => true,
                'data' => [
                    'token' => $jwt
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Password Salah!'
            ]);
        }
    }
}
