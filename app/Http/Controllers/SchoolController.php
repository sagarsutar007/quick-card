<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

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

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = uniqid('profile_', true) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/images/profile');
            $file->move($destinationPath, $filename);
            $user->profile_image = $filename;
        }

        User::create($validated);
        ActivityLogger::log('Set Authority', 'Added ' . str_ireplace(' user', '', $validated['name']) . ' user as authority!');
        return redirect()->route('management.schools')->with('success', 'Authority set successfully!');
    }

    public function getAll(Request $request)
    {
        if ($request->ajax()) {
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
                    return '
                        <div class="d-inline-flex gap-1">
                            <a href="'.route('school.students', $row->id).'" class="btn btn-sm btn-success"><i class="bi bi-eye"></i></a>
                            <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editSchoolModal" 
                                data-id="'.$row->id.'" 
                                data-id_card="'.e($row->id_card).'" 
                                data-amount="'.e($row->amount).'" 
                                data-payment="'.e($row->payment_details).'">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="'.route('schools.delete', $row->id).'" method="POST" class="d-inline delete-form">
                                '.csrf_field().method_field('DELETE').'
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>';
                })
                ->rawColumns(['code', 'name', 'status', 'photo_count', 'action'])
                ->make(true);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_card' => 'nullable|string',
            'amount' => 'nullable|string',
            'payment_details' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $school = School::findOrFail($id);
        $school->update($request->only('id_card', 'amount', 'payment_details', 'description'));
        ActivityLogger::log('Update School', 'Updated ' . str_ireplace(' school', '', $school->school_name) . ' school\'s text fields!');
        return redirect()->back()->with('success', 'School info updated successfully.');
    }

}
