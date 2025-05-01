<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthService
{
    /**
     * Attempt to log in a user with the given credentials.
     *
     * @param array $credentials An array containing 'email' and 'password'.
     * 
     * @return array
     * Returns an array with login status, message, user (if successful), token, and HTTP status code.
     */
    public function login(array $credentials)
    {
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return [
                'status' => 'error',
                'message' => 'غير مصرح به',
                'code' => 401,
            ];
        }

        return [
            'status' => 'success',
            'user' => Auth::guard('api')->user(),
            'token' => $token,
            'code' => 200,
        ];
    }

    /**
     * Register a new user with the given data.
     *
     * @param array $data An array containing 'name', 'email', and 'password'.
     * 
     * @return array
     * Returns an array with registration status, message, the created user, authentication token, and HTTP status code.
     */
    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // $user->assignRole('client');

        $token = Auth::guard('api')->login($user);

        return [
            'status' => 'success',
            'message' => 'تم انشاء المستخدم بنجاح',
            'user' => $user,
            'token' => $token,
            'code' => 201,
        ];
    }

    /**
     * Log out the currently authenticated user.
     * 
     * @return array
     * Returns an array with logout status, message, and HTTP status code.
     */
    public function logout()
    {
        Auth::guard('api')->logout();

        return [
            'status' => 'success',
            'message' => 'تم تسجيل الخروج بنجاح',
            'code' => 200,
        ];
    }
}
