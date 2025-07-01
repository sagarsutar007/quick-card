<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\ActivityLogger;
use App\Models\User;
use App\Models\UserActivity;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index() {
        $currentUser = auth()->user();
        $usersQuery = \App\Models\User::query();

        if ($currentUser->hasRole('superadmin')) {
            $usersQuery->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'superadmin');
            });
        } elseif ($currentUser->hasRole('admin')) {
            $usersQuery->whereHas('roles', function ($query) {
                $query->whereIn('name', ['authority', 'staff']);
            });
        } else {
            abort(403, 'Unauthorized access.');
        }

        $users = $usersQuery->with('roles')->get();

        return view('users.index', compact('users'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user_name = $user->name;
        if (!empty($user->profile_image)) {
            $imagePath = public_path('uploads/images/profile/' . $user->profile_image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $user->syncRoles([]);
        $user->delete();

        ActivityLogger::log('Delete User', 'Deleted ' . str_ireplace(' user', '', $user_name) . ' user from platform!');

        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function create()
    {
        $roles = Role::pluck('name', 'id');
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'address' => 'nullable|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'profile_image' => 'nullable|image|max:20480',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'address']);
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = uniqid('profile_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/images/profile'), $filename);
            $data['profile_image'] = $filename;
        }

        $user = User::create($data);
        $role = Role::findById($request->role_id);
        $user->assignRole($role->name);

        return redirect()->route('management.users')->with('success', 'User created successfully.');
    }

    public function view($id)
    {
        $user = User::find($id);
        return view('users.view', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $permissions = Permission::all();
        
        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function uploadProfileImage(Request $request, $id)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpg,jpeg,png,gif|max:20480',
        ]);

        $user = User::findOrFail($id);
        
        if ($user->profile_image && file_exists(public_path('uploads/images/profile/' . $user->profile_image))) {
            unlink(public_path('uploads/images/profile/' . $user->profile_image));
        }
        
        $file = $request->file('profile_image');
        $filename = uniqid('profile_', true) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/images/profile'), $filename);

        $user->profile_image = $filename;
        $user->save();

        return response()->json([
            'message' => 'Profile image uploaded successfully',
            'image_url' => asset('uploads/images/profile/' . $filename)
        ]);
    }


    public function updatePassword(Request $request, $userId)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = User::findOrFail($userId);
        
        $user->password = Hash::make($request->password);
        $user->save();
        
        ActivityLogger::log('Password Update', 'User password updated successfully by superadmin', $user->id);
        
        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    public function updateProfile(Request $request, $userId) 
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
            'facebook' => 'nullable|url|max:255',
            'whatsapp' => 'nullable|numeric|digits_between:7,15',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'threads' => 'nullable|url|max:255',
        ]);
        
        $user = User::findOrFail($userId);
        
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
        $user->facebook = $request->facebook;
        $user->whatsapp = $request->whatsapp;
        $user->twitter = $request->twitter;
        $user->instagram = $request->instagram;
        $user->youtube = $request->youtube;
        $user->threads = $request->threads;

        $user->save();

        ActivityLogger::log('Profile Update', 'User profile updated successfully');
        
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->syncRoles($request->role);
        
        return redirect()->back()->with('success', 'Role updated successfully');
    }

    public function updatePermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->syncPermissions($request->permissions ?? []);
        
        return redirect()->back()->with('success', 'Permissions updated successfully');
    }
}
