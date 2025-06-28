<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;

use App\Helpers\ActivityLogger;
use App\Models\School;
use App\Models\Student;

class StudentController extends Controller
{
    public function index($id) {
        $school = School::find($id);
        return view('student.index', compact('id', 'school'));
    }

    public function showStudents() {
        return view('student.showAllStudents');
    }

    public function getStudents($id)
    {
        $students = Student::with('school', 'creator')
            ->when($id, function ($query) use ($id) {
                $query->where('school_id', $id);
            })
            ->select('students.*');

        return DataTables::of($students)
            ->filterColumn('status', function ($query, $keyword) {
                $keyword = strtolower(trim($keyword));

                if ($keyword === 'uploaded') {
                    $query->where('status', 1);
                } elseif ($keyword === 'not uploaded') {
                    $query->where('status', 0);
                } elseif (str_contains($keyword, 'uploaded') && !str_contains($keyword, 'not')) {
                    $query->where('status', 1);
                } elseif (str_contains($keyword, 'not')) {
                    $query->where('status', 0);
                }
            })
            ->editColumn('name', fn($row) =>
                '<span class="editable" contenteditable="true" data-id="'.$row->id.'" data-field="name">'.e($row->name).'</span>')
            ->editColumn('class', fn($row) =>
                '<span class="editable" contenteditable="true" data-id="'.$row->id.'" data-field="class">'.e($row->class).'</span>')
            ->editColumn('dob', fn($row) =>
                '<span class="editable" contenteditable="true" data-id="'.$row->id.'" data-field="dob">'.e($row->dob ? date('d/m/Y', strtotime($row->dob)) : 'N/A').'</span>')
            ->editColumn('photo', fn($row) => $row->photo 
                ? '<img src="'.asset('uploads/images/students/' . $row->photo).'" width="40">' 
                : 'N/A')
            ->editColumn('status', fn($row) => $row->status
                ? '<span class="badge bg-success">Uploaded</span>'
                : '<span class="badge bg-danger">Not Uploaded</span>')
            ->editColumn('created_by', fn($row) => $row->creator?->name ?? 'N/A')
            ->editColumn('created_at', fn($row) => $row->created_at?->format('d M Y h:i A') ?? '')
            ->editColumn('updated_at', fn($row) => $row->updated_at?->format('d M Y h:i A') ?? '')
            ->addColumn('action', function ($row) {
                $viewBtn = '';
                if (!empty($row->photo) && file_exists(public_path('uploads/images/students/' . $row->photo))) {
                    $viewBtn = '<a href="'.asset('uploads/images/students/' . $row->photo).'" class="btn btn-sm btn-primary view-photo" data-fancybox="gallery" data-caption="'.e($row->name).'">
                                    <i class="bi bi-image"></i>
                                </a>';
                }

                $downloadBtn = (!empty($row->photo) && file_exists(public_path('uploads/images/students/' . $row->photo)))
                    ? '<a href="/uploads/images/students/'.$row->photo.'" download class="btn btn-sm btn-success"><i class="bi bi-download"></i></a>'
                    : '';

                $uploadBtn = '<button data-id="' . $row->id . '" class="btn btn-sm btn-info upload-photo"><i class="bi bi-upload"></i></button>';

                return '
                    <div class="d-inline-flex gap-1">
                        ' . $viewBtn . $downloadBtn . $uploadBtn . '
                        <form action="' . route('students.delete', $row->id) . '" method="POST" class="d-inline delete-form">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>';
            })
            
            ->rawColumns(['name', 'class', 'dob', 'status', 'photo', 'action'])
            ->make(true);
    }

    public function addStudent(Request $request) {
        $schoolId = $request->get('school_id');
        $school = School::find($schoolId);
        return view('student.add', compact('schoolId', 'school'));
    }

    public function uploadPhoto(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/images/students');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);

            return response()->json([
                'file_name' => $filename,
                'file_path' => 'uploads/images/students/' . $filename
            ]);
        }

        return response()->json(['error' => 'File upload failed'], 400);
    }

    public function uploadStudentPhoto(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $student = Student::findOrFail($request->student_id);

        $file = $request->file('photo');
        $ext = $file->getClientOriginalExtension();
        $filename = $student->id . '.' . $ext;
        $path = public_path('uploads/images/students/');

        $file->move($path, $filename);

        $student->photo = $filename;
        $student->status = 1;
        $student->updated_by = auth()->id();
        $student->save();

        return response()->json(['message' => 'Photo uploaded successfully']);
    }

    public function saveStudent(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'class'      => 'required|string|max:50',
            'dob'        => 'nullable|date',
            'school_id'  => 'required|integer|exists:schools,id',
            'photo'      => 'nullable|string|max:255',
        ]);
        
        $student = new Student();
        $student->name       = $request->name;
        $student->class      = $request->class;
        $student->dob        = $request->dob;
        $student->school_id  = $request->school_id;
        $student->status     = 0;
        $student->created_by = Auth::id();

        $student->save();

        if ($request->filled('photo')) {
            $originalPath = public_path($request->photo);
            if (file_exists($originalPath)) {
                $extension = pathinfo($originalPath, PATHINFO_EXTENSION);
                $newFileName = $student->id . '.' . $extension;
                $newPath = public_path('uploads/images/students/' . $newFileName);

                if (rename($originalPath, $newPath)) {
                    $student->photo = $newFileName;
                    $student->status = 1;
                    $student->save();
                }
            }
        }

        $school = School::find($student->school_id);

        ActivityLogger::log('Add Student', 'Added student ' . ucwords($student->name) . ' to ' . $school->school_name);

        return redirect()->back()->with('success', 'Student saved successfully!');
    }

    public function inlineUpdate(Request $request, $id)
    {
        $request->validate([
            'field' => 'required|in:name,class,dob',
            'value' => 'required|string|max:255',
        ]);

        $student = Student::findOrFail($id);
        $originalValue = $student->{$request->field};
        $name = $student->name;
        $school = School::find($student->school_id);

        if ($request->field === 'dob') {
            try {
                $newDob = \Carbon\Carbon::createFromFormat('d/m/Y', $request->value)->format('Y-m-d');
            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid date format. Use DD/MM/YYYY'], 422);
            }
            
            if ($student->dob === $newDob) {
                return response()->json(['info' => 'Nothing was updated. Value is the same.'], 200);
            }

            $student->dob = $newDob;
        } else {
            if ($student->{$request->field} === $request->value) {
                return response()->json(['info' => 'Nothing was updated. Value is the same.'], 200);
            }

            $student->{$request->field} = $request->value;
        }

        dd($student->dob, $request->value);
        $student->updated_by = auth()->id();
        $student->save();

        ActivityLogger::log(
            'Update Student',
            'Updated ' . $request->field . ' of student ' . ucwords($name) . ' of ' . $school->school_name
        );

        return response()->json(['message' => 'Updated successfully']);
    }

    public function getPhotos($id)
    {
        $student = Student::findOrFail($id);

        if (!$student->photo || !file_exists(public_path('uploads/images/students/' . $student->photo))) {
            return response()->json(['photos' => []]);
        }
        
        return response()->json([
            'photos' => [$student->photo]
        ]);
    }

    public function deleteStudent($id)
    {
        $student = Student::findOrFail($id);
        
        if ($student->photo && file_exists(public_path('uploads/images/students/' . $student->photo))) {
            unlink(public_path('uploads/images/students/' . $student->photo));
        }

        $student->delete();

        return redirect()->back()->with('message', 'Student deleted successfully');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
            'school_id' => 'required|exists:schools,id',
        ]);

        $collection = Excel::toCollection(null, $request->file('excel_file'));
        $rows = $collection->first();

        foreach ($rows->skip(1) as $row) {
            $data = $row->toArray();
            
            if (empty($data[0]) || empty($data[1]) || empty($data[2])) {
                continue;
            }

            try {
                $dob = \Carbon\Carbon::parse($data[2])->format('Y-m-d');
            } catch (\Exception $e) {
                continue; 
            }
            
            $exists = Student::where('name', $data[0])
                ->where('class', $data[1])
                ->whereDate('dob', $dob)
                ->where('school_id', $request->school_id)
                ->exists();

            if ($exists) {
                continue;
            }

            Student::create([
                'name'       => $data[0],
                'class'      => $data[1],
                'dob'        => $dob,
                'school_id'  => $request->school_id,
                'status'     => 0,
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->back()->with('success', 'Excel imported successfully.');
    }

    public function getAllStudents(Request $request)
    {
        if ($request->ajax()) {
            $schools = Student::select(
                    'students.*',
                    'schools.school_name as school_name',
                    'districts.name as district_name',
                    'blocks.name as block_name',
                    'clusters.name as cluster_name',
                    'users.name as creator_name',
                )
                ->leftJoin('schools', 'schools.id', '=', 'students.school_id')
                ->leftJoin('districts', 'schools.district_id', '=', 'districts.id')
                ->leftJoin('blocks', 'schools.block_id', '=', 'blocks.id')
                ->leftJoin('clusters', 'schools.cluster_id', '=', 'clusters.id')
                ->leftJoin('users', 'students.created_by', '=', 'users.id');

            return DataTables::of($schools)
                ->filterColumn('status', function ($query, $keyword) {
                    $keyword = strtolower(trim($keyword));

                    if ($keyword === 'uploaded') {
                        $query->where('status', 1);
                    } elseif ($keyword === 'not uploaded') {
                        $query->where('status', 0);
                    } elseif (str_contains($keyword, 'uploaded') && !str_contains($keyword, 'not')) {
                        $query->where('status', 1);
                    } elseif (str_contains($keyword, 'not')) {
                        $query->where('status', 0);
                    }
                })
                ->addIndexColumn()
                ->editColumn('name', fn($row) =>
                    '<span class="editable" contenteditable="true" data-id="'.$row->id.'" data-field="name">'.e($row->name).'</span>')
                ->editColumn('class', fn($row) =>
                    '<span class="editable" contenteditable="true" data-id="'.$row->id.'" data-field="class">'.e($row->class).'</span>')
                ->editColumn('dob', fn($row) =>
                '<span class="editable" contenteditable="true" data-id="'.$row->id.'" data-field="dob">'.e($row->dob ? date('d/m/Y', strtotime($row->dob)) : 'N/A').'</span>')
                ->editColumn('photo', fn($row) => $row->photo 
                ? '<img src="'.asset('uploads/images/students/' . $row->photo).'" width="40">' 
                : 'N/A')
                ->editColumn('school', function ($row) {
                    return '<a href="'.route('school.students', $row->school_id).'" class="btn btn-sm btn-link">'.$row->school_name.'</a>';
                })
                ->editColumn('district', fn($row) => $row->district_name ?? 'N/A')
                ->editColumn('block', fn($row) => $row->block_name ?? 'N/A')
                ->editColumn('cluster', fn($row) => $row->cluster_name ?? 'N/A')
                ->editColumn('status', fn($row) => $row->status
                    ? '<span class="badge bg-success">Uploaded</span>'
                    : '<span class="badge bg-danger">Not Uploaded</span>')
                ->editColumn('created_by', fn($row) => $row->creator?->name ?? 'N/A')
                ->editColumn('created_at', fn($row) => $row->created_at?->format('d M Y h:i A') ?? '')
                ->editColumn('updated_at', fn($row) => $row->updated_at?->format('d M Y h:i A') ?? '')
                ->addColumn('action', function ($row) {
                    $viewBtn = '';
                    if (!empty($row->photo) && file_exists(public_path('uploads/images/students/' . $row->photo))) {
                        $viewBtn = '<a href="'.asset('uploads/images/students/' . $row->photo).'" class="btn btn-sm btn-primary view-photo" data-fancybox="gallery" data-caption="'.e($row->name).'">
                                        <i class="bi bi-image"></i>
                                    </a>';
                    }

                    $downloadBtn = (!empty($row->photo) && file_exists(public_path('uploads/images/students/' . $row->photo)))
                        ? '<a href="/uploads/images/students/'.$row->photo.'" download class="btn btn-sm btn-success"><i class="bi bi-download"></i></a>'
                        : '';

                    $uploadBtn = '<button data-id="' . $row->id . '" class="btn btn-sm btn-info upload-photo"><i class="bi bi-upload"></i></button>';

                    return '
                        <div class="d-inline-flex gap-1">
                            ' . $viewBtn . $downloadBtn . $uploadBtn . '
                            <form action="' . route('students.delete', $row->id) . '" method="POST" class="d-inline delete-form">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>';
                })
                ->rawColumns(['name', 'class', 'dob', 'photo', 'school', 'status', 'action'])
                ->make(true);
        }
    }
    
}
