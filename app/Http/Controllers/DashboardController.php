<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;

use App\Models\School;
use App\Models\Student;
use App\Models\UserActivity;

class DashboardController extends Controller
{
    public function showDashboard(Request $request)
    {
        $loggedInUser = auth()->user();
        $userRole = $loggedInUser->getRoleNames()->first();

        $schoolCount = 0;
        $studentCount = 0;
        $studentsWithPhoto = 0;
        $latestActivities = collect();

        if ($userRole === 'superadmin' || $userRole === 'admin') {
            $schoolCount = School::count();
            $studentCount = Student::count();
            $studentsWithPhoto = Student::whereNotNull('photo')->where('photo', '!=', '')->count();
            
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
                                        
            $latestActivities = UserActivity::where('user_id', $loggedInUser->id)
                ->latest()
                ->take(10)
                ->get();
        } elseif ($userRole === 'authority') {
            $schoolId = $loggedInUser->school_id;
            $schoolCount = $schoolId ? 1 : 0;
            $studentCount = Student::where('school_id', $schoolId)->count();
            $studentsWithPhoto = Student::where('school_id', $schoolId)
                                        ->whereNotNull('photo')
                                        ->where('photo', '!=', '')
                                        ->count();
                                        
            $latestActivities = UserActivity::where('user_id', $loggedInUser->id)
                ->latest()
                ->take(10)
                ->get();
        }

        $studentsWithoutPhoto = $studentCount - $studentsWithPhoto;

        return view('dashboard', compact(
            'schoolCount',
            'studentCount',
            'studentsWithPhoto',
            'studentsWithoutPhoto',
            'latestActivities'
        ));
    }

}
