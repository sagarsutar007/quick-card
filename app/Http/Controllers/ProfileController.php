<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

use App\Models\UserActivity;

use App\Helpers\ActivityLogger;
use App\Models\School;
use App\Models\Student;

class ProfileController extends Controller
{
    public function myProfile()
    {
        $user = Auth::user();
        $userRole = $user->getRoleNames()->first();
        $schoolCount = 0;
        $studentCount = 0;
        $studentsWithPhoto = 0;
        $studentsLocked = 0;

        if ($userRole === 'superadmin' || $userRole === 'admin') {
            $schoolCount = School::count();
            $studentCount = Student::count();
            $studentsWithPhoto = Student::whereNotNull('photo')->where('photo', '!=', '')->count();
            $studentsLocked = Student::whereNotNull('photo')->where('lock', 1)->count();
        } elseif ($userRole === 'staff') {
            $schoolIds = $user->schools->pluck('id');
            $schoolCount = $schoolIds->count();
            $studentCount = Student::whereIn('school_id', $schoolIds)->count();
            $studentsWithPhoto = Student::whereIn('school_id', $schoolIds)
                                        ->whereNotNull('photo')
                                        ->where('photo', '!=', '')
                                        ->count();

            $studentsLocked = Student::whereIn('school_id', $schoolIds)
                                        ->whereNotNull('photo')
                                        ->where('lock', 1)
                                        ->count();
        } elseif ($userRole === 'authority') {
            $schoolId = $user->school_id;
            $schoolCount = $schoolId ? 1 : 0;
            $studentCount = Student::where('school_id', $schoolId)->count();
            $studentsWithPhoto = Student::where('school_id', $schoolId)
                                        ->whereNotNull('photo')
                                        ->where('photo', '!=', '')
                                        ->count();
            $studentsLocked = Student::where('school_id', $schoolId)
                                        ->whereNotNull('photo')
                                        ->where('lock', 1)
                                        ->count();
        }

        $studentsWithoutPhoto = $studentCount - $studentsWithPhoto;

        return view('my-profile', compact(
            'user',
            'schoolCount',
            'studentCount',
            'studentsWithPhoto',
            'studentsWithoutPhoto',
            'studentsLocked'
        ));
    }
    

    public function updateMyProfile(Request $request) 
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'address' => 'nullable|string|max:500',
            'about' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',
            'gender' => 'required|in:male,female',
        ]);
        
        $user = Auth::user();
        
        $user->fill($validated);
        
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = uniqid('profile_', true) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/images/profile');
            $file->move($destinationPath, $filename);
            $user->profile_image = $filename;
        }
        
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = uniqid('cover_', true) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/images/cover');
            $file->move($destinationPath, $filename);
            $user->cover_image = $filename;
        }
        
        $user->name = $validated['name'];
        $user->designation = $validated['designation'] ?? null;
        $user->phone = $validated['phone'];
        $user->email = $validated['email'];
        $user->address = $validated['address'] ?? null;
        $user->about = $validated['about'] ?? null;

        $user->save();

        ActivityLogger::log('Profile Update', 'User profile updated successfully');
        
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function updateMyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        ActivityLogger::log('Password Update', 'User password updated successfully');
        
        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    public function updateMySocialLinks(Request $request)
    {
        $request->validate([
            'facebook' => 'nullable|url|max:255',
            'whatsapp' => 'nullable|numeric|digits_between:7,15',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'threads' => 'nullable|url|max:255',
        ]);

        $user = Auth::user();

        $user->facebook = $request->facebook;
        $user->whatsapp = $request->whatsapp;
        $user->twitter = $request->twitter;
        $user->instagram = $request->instagram;
        $user->youtube = $request->youtube;
        $user->threads = $request->threads;

        $user->save();

        ActivityLogger::log('Social Links Update', 'User updated social media links');

        return redirect()->back()->with('success', 'Social media links updated successfully.');
    }

}
