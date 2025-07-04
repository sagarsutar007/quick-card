<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

use App\Helpers\ActivityLogger;
use App\Models\Block;
use App\Models\Cluster;
use App\Models\District;
use App\Models\School;
use App\Models\User;
use App\Models\Student;



class SchoolController extends Controller
{
    public function index() {
        return view('school.index');
    }

    public function showSchoolCreateForm() {
        $districts = District::where('status', 1)->orderBy('name')->get();
        $blocks = $districts->isNotEmpty() ? Block::where('district_id', $districts->first()->id)->where('status', 1)->orderBy('name')->get() : collect();
        $clusters = $blocks->isNotEmpty() ? Cluster::where('block_id', $blocks->first()->id)->where('status', 1)->orderBy('name')->get() : collect();
        return view('school.create', compact('districts', 'blocks', 'clusters'));
    }

    public function addSchool(Request $request)
    {
        $validated = $request->validate([
            'school_code'   => 'required|string|max:50|unique:schools,school_code',
            'school_name'   => 'required|string|max:255',
            'udise_no'      => 'nullable|string|max:50',
            'school_address'=> 'nullable|string|max:1000',
            'district_id'   => 'required|exists:districts,id',
            'block_id'      => 'required|exists:blocks,id',
            'cluster_id'    => 'required|exists:clusters,id',
            'status'        => 'required|in:0,1',
        ]);

        $validated['created_by'] = Auth::id();

        $school = School::create($validated);

        ActivityLogger::log('Add School', 'Added ' . str_ireplace(' school', '', $validated['school_name']) . ' school');

        return redirect()->route('schools.setAuthorityForm', $school->id)->with('success', 'School created! Please set authority person.');
    }

    public function showSetAuthorityForm($schoolId)
    {
        $school = School::findOrFail($schoolId);
        return view('school.set_authority', compact('school'));
    }

    public function saveAuthority(Request $request, $schoolId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'gender' => 'nullable|in:male,female,other',
            'dob' => 'nullable|date',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',
            'status' => 'required|in:0,1'
        ]);

        $validated['school_id'] = $schoolId;
        $validated['password'] = Hash::make($validated['password']);
        
        $user = new User($validated);
        
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = uniqid('profile_', true) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/images/profile');
            $file->move($destinationPath, $filename);
            $user->profile_image = $filename;
        }

        $user->save();
        
        $user->assignRole('authority');
        
        ActivityLogger::log('Set Authority', 'Added ' . str_ireplace(' user', '', $validated['name']) . ' user as authority!');

        return redirect()->route('management.schools')->with('success', 'Authority set successfully!');
    }

    public function getAll(Request $request)
    {
        if ($request->ajax()) {
            $loggedInUser = auth()->user();
            $userRole = $loggedInUser->getRoleNames()->first();

            $schools = School::select(
                    'schools.*',
                    'districts.name as district_name',
                    'blocks.name as block_name',
                    'clusters.name as cluster_name',
                    'users.name as creator_name',
                    DB::raw('(SELECT COUNT(*) FROM students WHERE students.school_id = schools.id) as students_count'),
                    DB::raw('(SELECT COUNT(*) FROM students WHERE students.school_id = schools.id AND students.photo IS NOT NULL AND students.photo != "") as photo_count'),
                    DB::raw('(SELECT COUNT(*) FROM students WHERE students.school_id = schools.id AND students.photo IS NULL OR students.photo = "") as no_photo_count')
                )
                ->leftJoin('districts', 'schools.district_id', '=', 'districts.id')
                ->leftJoin('blocks', 'schools.block_id', '=', 'blocks.id')
                ->leftJoin('clusters', 'schools.cluster_id', '=', 'clusters.id')
                ->leftJoin('users', 'schools.created_by', '=', 'users.id');

            if (in_array($userRole, ['authority', 'custom'])) {
                if ($loggedInUser->school_id) {
                    $schools->where('schools.id', $loggedInUser->school_id);
                } else {
                    $schools->whereNull('schools.id');
                }
            } elseif ($userRole === 'staff') {
                $schoolIds = $loggedInUser->schools->pluck('id')->toArray();
                $schools->whereIn('schools.id', $schoolIds);
            }

            return DataTables::of($schools)
                ->addIndexColumn()
                ->editColumn('code', function ($row) {
                    return '<a href="'.route('school.students', $row->id).'" class="btn btn-sm btn-link">'.$row->school_code.'</a>';
                })
                ->editColumn('name', function ($row) {
                    return '<a href="'.route('school.students', $row->id).'" class="btn btn-sm btn-link">'.$row->school_name.'</a>';
                })
                ->editColumn('district', fn($row) => $row->district_name ?? 'N/A')
                ->editColumn('block', fn($row) => $row->block_name ?? 'N/A')
                ->editColumn('cluster', fn($row) => $row->cluster_name ?? 'N/A')
                ->editColumn('description', fn($row) => $row->description)
                ->editColumn('students_count', fn($row) => $row->students_count)
                ->editColumn('photo_count', function ($row) {
                    return '<span class="text-success fw-bold">'.$row->photo_count.'</span> / <span class="text-danger fw-bold">'.$row->no_photo_count.'</span>';
                })
                ->editColumn('id_card', fn($row) => $row->id_card ?? 'N/A')
                ->editColumn('amount', fn($row) => $row->amount ?? 'N/A')
                ->editColumn('payment_details', fn($row) => $row->payment_details ?? 'N/A')
                ->editColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->editColumn('created_by', fn($row) => $row->creator_name ?? 'N/A')
                ->editColumn('created_at', fn($row) => $row->created_at ? $row->created_at->format('d M Y h:i A') : '')
                ->editColumn('updated_at', fn($row) => $row->updated_at ? $row->updated_at->format('d M Y h:i A') : '')
                ->addColumn('action', function ($row) {
                    $buttons = '<div class="d-inline-flex gap-1">';

                        if (auth()->user()->can('view school students')) {
                            $buttons .= '<a href="'.route('school.students', $row->id).'" class="btn btn-sm btn-success" title="View Students"><i class="bi bi-eye"></i></a>';
                        }

                        if (auth()->user()->can('edit school')) {
                            $buttons .= '<a href="'.route('school.edit', $row->id).'" class="btn btn-sm btn-success" title="Edit School"><i class="bi bi-pencil"></i></a>';
                        }

                        if (auth()->user()->can('make notes')) {
                            $buttons .= '<a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editSchoolModal" 
                                data-id="'.$row->id.'" 
                                data-id_card="'.e($row->id_card).'" 
                                data-amount="'.e($row->amount).'" 
                                data-description="'.e($row->description).'" 
                                data-payment="'.e($row->payment_details).'"
                                title="Edit">
                                <i class="ti ti-notes"></i></a>';
                        }

                        if (auth()->user()->can('assign school')) {
                            $buttons .= '<a href="'.route('school.assignedUsers', $row->id).'" class="btn btn-sm btn-warning" title="Assigned Users"><i class="ti ti-users"></i></a>';
                        }

                        if (auth()->user()->can('delete school')) {
                            $buttons .= '<form action="'.route('schools.delete', $row->id).'" method="POST" class="d-inline delete-form">'
                                .csrf_field().method_field('DELETE').'
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </form>';
                        }

                        $buttons .= '</div>';

                        return $buttons;

                })
                ->rawColumns(['code', 'name', 'status', 'photo_count', 'action'])
                ->make(true);
        }
    }

    public function editSchool(Request $request, $id) 
    {
        $school = School::findOrFail($id);

        $districts = District::where('status', 1)->orderBy('name')->get();
        
        $blocks = $school->district_id 
            ? Block::where('district_id', $school->district_id)->where('status', 1)->orderBy('name')->get()
            : collect();
            
        $clusters = $school->block_id 
            ? Cluster::where('block_id', $school->block_id)->where('status', 1)->orderBy('name')->get()
            : collect();

        return view('school.edit', compact('school', 'districts', 'blocks', 'clusters'));
    }


    public function updateSchool(Request $request, $id)
    {
        $school = School::findOrFail($id);

        $request->validate([
            'school_code' => 'required|string|max:255|unique:schools,school_code,' . $school->id,
            'school_name' => 'required|string|max:255',
            'udise_no' => 'nullable|string|max:255|unique:schools,udise_no,' . $school->id,
            'district_id' => 'required|exists:districts,id',
            'block_id' => 'required|exists:blocks,id',
            'cluster_id' => 'required|exists:clusters,id',
            'school_address' => 'nullable|string|max:500',
            'status' => 'required|in:0,1',
        ]);

        $school->update([
            'school_code' => $request->school_code,
            'school_name' => $request->school_name,
            'udise_no' => $request->udise_no,
            'district_id' => $request->district_id,
            'block_id' => $request->block_id,
            'cluster_id' => $request->cluster_id,
            'school_address' => $request->school_address,
            'status' => $request->status,
        ]);

        ActivityLogger::log('Updated School', 'Updated school: ' . $school->school_name);

        return redirect()->route('management.schools')->with('success', 'School updated successfully.');
    }

    public function deleteSchool($id)
    {
        $school = School::findOrFail($id);
        $school_name = $school->school_name;
        
        foreach ($school->students as $student) {
            if ($student->photo && file_exists(public_path('uploads/images/students/' . $student->photo))) {
                unlink(public_path('uploads/images/students/' . $student->photo));
            }
        }
        
        $school->students()->delete();
        
        $school->delete();

        ActivityLogger::log('Delete School', 'Deleted ' . str_ireplace(' school', '', $school_name ) . ' school and students');

        return redirect()->back()->with('success', 'School and associated students removed.');
    }

    public function assignedUsers($id)
    {
        $school = School::findOrFail($id);

        $excludedRoles = ['admin', 'superadmin'];
        
        $users = User::whereDoesntHave('roles', function ($query) use ($excludedRoles) {
            $query->whereIn('name', $excludedRoles);
        })
        ->with(['roles', 'school', 'schools'])
        ->get();

        return view('school.assign', compact('users', 'school'));
    }

    public function assignUser(School $school, User $user)
    {
        $role = $user->getRoleNames()->first();

        if ($role === 'authority') {
            $user->school_id = $school->id;
            $user->save();
        } elseif ($role === 'staff') {
            $user->schools()->attach($school->id);
        }

        ActivityLogger::log(
            'Assigned User',
            'User "' . $user->name . '" (' . $role . ') was assigned to school "' . $school->school_name . '"'
        );

        return back()->with('success', 'User assigned successfully.');
    }

    public function unassignUser(School $school, User $user)
    {
        $role = $user->getRoleNames()->first();

        if ($role === 'authority') {
            if ($user->school_id == $school->id) {
                $user->school_id = null;
                $user->save();
            }
        } elseif ($role === 'staff') {
            $user->schools()->detach($school->id);
        }

        ActivityLogger::log(
            'Unassigned User',
            'User "' . $user->name . '" (' . $role . ') was unassigned from school "' . $school->school_name . '"'
        );

        return back()->with('success', 'User unassigned successfully.');
    }
}
