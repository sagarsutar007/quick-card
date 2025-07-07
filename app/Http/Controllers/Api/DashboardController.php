<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\School;
use App\Models\Student;
use App\Models\UserActivity;

class DashboardController extends Controller
{
    public function show(Request $request)
    {
        $loggedInUser = auth()->user();
        $userRole = $loggedInUser->getRoleNames()->first();

        $schoolCount = 0;
        $studentCount = 0;
        $studentsWithPhoto = 0;
        $latestActivities = collect();
        $basicSchoolDetails = collect();

        if ($userRole === 'superadmin' || $userRole === 'admin') {
            $schoolCount = School::count();
            $studentCount = Student::count();
            $studentsWithPhoto = Student::whereNotNull('photo')->where('photo', '!=', '')->count();

            $basicSchoolDetails = School::select('id', 'school_name as name', 'school_address')
                ->withCount([
                    'students as students_without_photo_count' => function ($query) {
                        $query->whereNull('photo')->orWhere('photo', '');
                    }
                ])
                ->limit(3)
                ->get();

            $latestActivities = UserActivity::with('user')
                ->latest()
                ->take(10)
                ->get();
        } elseif ($userRole === 'staff') {
            $schoolIds = $loggedInUser->schools->pluck('id');
            $schoolCount = $schoolIds->count();
            $studentCount = Student::whereIn('school_id', $schoolIds)->count();
            $studentsWithPhoto = Student::whereIn('school_id', $schoolIds)
                ->whereNotNull('photo')
                ->where('photo', '!=', '')
                ->count();

            $basicSchoolDetails = School::select('id', 'school_name as name', 'school_address')
                ->whereIn('id', $schoolIds)
                ->withCount([
                    'students as students_without_photo_count' => function ($query) {
                        $query->whereNull('photo')->orWhere('photo', '');
                    }
                ])
                ->limit(3)
                ->get();

            $latestActivities = UserActivity::where('user_id', $loggedInUser->id)
                ->latest()
                ->take(10)
                ->get();
        } elseif ($userRole === 'authority' || $userRole === 'custom') {
            $schoolId = $loggedInUser->school_id;
            $schoolCount = $schoolId ? 1 : 0;
            $studentCount = Student::where('school_id', $schoolId)->count();
            $studentsWithPhoto = Student::where('school_id', $schoolId)
                ->whereNotNull('photo')
                ->where('photo', '!=', '')
                ->count();

            $basicSchoolDetails = School::select('id', 'school_name as name', 'school_address')
                ->where('id', $schoolId)
                ->withCount([
                    'students as students_without_photo_count' => function ($query) {
                        $query->whereNull('photo')->orWhere('photo', '');
                    }
                ])
                ->limit(3)
                ->get();

            $latestActivities = UserActivity::where('user_id', $loggedInUser->id)
                ->latest()
                ->take(10)
                ->get();
        }

        $studentsWithoutPhoto = $studentCount - $studentsWithPhoto;

        return response()->json([
            'school_count' => $schoolCount,
            'student_count' => $studentCount,
            'students_with_photo' => $studentsWithPhoto,
            'students_without_photo' => $studentsWithoutPhoto,
            'latest_activities' => $latestActivities,
            'school_basic_info' => $basicSchoolDetails,
        ]);
    }

}
