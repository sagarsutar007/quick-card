<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\ActivityLogger;
use App\Models\School;
use App\Models\User;



class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'emailOrPhone' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = filter_var($request->emailOrPhone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $loginField => $request->emailOrPhone,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials. Please check your email/phone and password.'
            ], 401);
        }

        $user = Auth::user();

        if ($user->profile_image) {
            $user->profile_image = url('uploads/images/profile/' . $user->profile_image);
        }
        
        ActivityLogger::log('Login', 'User logged in successfully via API');

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken,
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $user,
        ]);
    }

    
}
