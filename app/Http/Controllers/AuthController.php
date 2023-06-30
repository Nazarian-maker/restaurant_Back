<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->only(['email', 'password', 'pin_code']);

            if (isset($credentials['pin_code'])) {
                // Авторизация по пин-коду
                $user = User::where('pin_code', $credentials['pin_code'])->first();

                if (!$user) {
                    return response([
                        'message' => 'Pin code does not match with our record.',
                    ], 401);
                }
                return response([
                    'message' => 'User Logged In Successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 200);
            } else {
                // Авторизация по email и password
                $validateUser = Validator::make($credentials, [
                    'email' => 'required|email',
                    'password' => 'required',
                ]);

                if ($validateUser->fails()) {
                    return response([
                        'message' => 'validation error',
                        'errors' => $validateUser->errors()
                    ], 401);
                }

                $user = User::where('email', $credentials['email'])->first();

                if (!$user) {
                    return response([
                        'message' => 'Email or Password does not match with our record.',
                    ], 401);
                }

                if (Hash::check($credentials['password'], $user->password) && $user->role_id != 3) {
                    return response([
                        'message' => 'User Logged In Successfully',
                        'token' => $user->createToken("API TOKEN")->plainTextToken
                    ], 200);
                } else {
                    return response([
                        'message' => 'Email or Password does not match with our record.',
                    ], 401);
                }
            }
        } catch (\Throwable $th) {
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete(); // отзыв текущего токена
        return response([
            'message' => 'Logged out'
        ], 200);
    }
}
