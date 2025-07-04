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

    public function getUserSchools(Request $request)
    {
        $user = $request->user();
        $role = $user->getRoleNames()->first();

        $schools = School::select(
                'schools.*',
                'districts.name as district_name',
                'blocks.name as block_name',
                'clusters.name as cluster_name',
                DB::raw('(SELECT COUNT(*) FROM students WHERE students.school_id = schools.id) as students_count'),
                DB::raw('(SELECT COUNT(*) FROM students WHERE students.school_id = schools.id AND students.photo IS NOT NULL AND students.photo != "") as photo_count'),
                DB::raw('(SELECT COUNT(*) FROM students WHERE students.school_id = schools.id AND (students.photo IS NULL OR students.photo = "")) as no_photo_count')
            )
            ->leftJoin('districts', 'schools.district_id', '=', 'districts.id')
            ->leftJoin('blocks', 'schools.block_id', '=', 'blocks.id')
            ->leftJoin('clusters', 'schools.cluster_id', '=', 'clusters.id');

        if (in_array($role, ['authority', 'custom'])) {
            if ($user->school_id) {
                $schools->where('schools.id', $user->school_id);
            } else {
                return response()->json([
                    'schools' => []
                ]);
            }
        } elseif ($role === 'staff') {
            $schoolIds = $user->schools()->pluck('schools.id');
            $schools->whereIn('schools.id', $schoolIds);
        } else {
            return response()->json([
                'message' => 'Access denied or not supported for this role.'
            ], 403);
        }

        return response()->json([
            'schools' => $schools->get()
        ]);
    }
}
