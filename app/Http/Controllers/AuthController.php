<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // wdNcIy4aPHAB7aA6Z3GG51WCwgopN08KNbVpyNlRy9R4LwH4hB2sZLSLgXRShXwH
    public function login(Request $request)
    {
        $credentials = $request->only("username", "password");
        $validator = Validator::make($credentials, [
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        if (!$token = JWTAuth::attempt([
            'nombre_usuario' => $request->username,
            'password' => $request->password
        ])
        ) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'access_token' => JWTAuth::refresh(),
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    public function me()
    {
        return response()->json(
            JWTAuth::user()
        );
    }
}
