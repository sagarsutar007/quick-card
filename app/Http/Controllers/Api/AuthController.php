<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\ActivityLogger;
use App\Models\School;
use App\Models\User;
use Spatie\Permission\Models\Permission;


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

        $permissions = $user->getAllPermissions()->pluck('name')->toArray();

        $userData = $user->only([
            'id', 'name', 'email', 'phone', 'profile_image', 'designation',
            'address', 'gender', 'dob', 'school_id', 'status', 'created_at', 'updated_at',
        ]);

        $userData = $user->only([
            'id',
            'name',
            'email',
            'phone',
            'profile_image',
            'designation',
            'address',
            'gender',
            'dob',
            'school_id',
            'status',
            'created_at',
            'updated_at',
            'about',
            'facebook',
            'twitter',
            'instagram',
            'youtube',
            'whatsapp',
            'threads',
            'website',
        ]);

        $userData['profile_image'] = $user->profile_image
            ? url('uploads/images/profile/' . $user->profile_image)
            : null;

        ActivityLogger::log('Login', 'User logged in successfully via API');

        return response()->json([
            'user' => $userData,
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
