<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:500',
            'about' => 'nullable|string|max:1000',
            'gender' => 'required|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = uniqid('profile_', true) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/images/profile'), $filename);
            $data['profile_image'] = $filename;
        }

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = uniqid('cover_', true) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/images/cover'), $filename);
            $data['cover_image'] = $filename;
        }

        $user->fill($data)->save();

        ActivityLogger::log('Update Profile', 'User updated their profile via API');

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();

        ActivityLogger::log('Password Update', 'User updated their password via API');

        return response()->json([
            'message' => 'Password updated successfully.'
        ]);
    }

    public function updateSocialLinks(Request $request)
    {
        $request->validate([
            'facebook' => 'nullable|url|max:255',
            'whatsapp' => 'nullable|numeric|digits_between:7,15',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'threads' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        $user = $request->user();

        $user->update([
            'facebook' => $request->facebook,
            'whatsapp' => $request->whatsapp,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
            'youtube' => $request->youtube,
            'threads' => $request->threads,
            'website' => $request->website,
        ]);

        ActivityLogger::log('Social Links Update', 'User updated social links via API');

        return response()->json([
            'message' => 'Social links updated successfully.',
            'user' => $user
        ]);
    }

}
