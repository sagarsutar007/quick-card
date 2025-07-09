<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Student;
use App\Models\School;

class StudentController extends Controller
{
    public function getSchoolStudents(Request $request, $schoolId)
    {
        $query = Student::where('school_id', $schoolId)
            ->where('status', 1);

        if ($request->has('q')) {
            $search = strtolower(trim($request->query('q')));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->has('status')) {
            $status = strtolower($request->query('status'));
            if ($status === 'uploaded') {
                $query->whereNotNull('photo')->where('photo', '!=', '');
            } elseif ($status === 'not uploaded') {
                $query->where(function ($q) {
                    $q->whereNull('photo')->orWhere('photo', '');
                });
            }
        }
        
        if ($class = $request->query('class')) {
            $query->where('class', $class);
        }

        if ($dob = $request->query('dob')) {
            $dob = trim($dob);
            try {
                if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dob)) {
                    $date = \Carbon\Carbon::createFromFormat('d/m/Y', $dob)->format('Y-m-d');
                    $query->whereDate('dob', $date);
                } elseif (preg_match('/^\d{2}\/\d{2}$/', $dob)) {
                    [$day, $month] = explode('/', $dob);
                    $query->whereDay('dob', $day)->whereMonth('dob', $month);
                } elseif (preg_match('/^\d{2}\/\d{4}$/', $dob)) {
                    [$month, $year] = explode('/', $dob);
                    $query->whereMonth('dob', $month)->whereYear('dob', $year);
                } elseif (preg_match('/^\d{4}$/', $dob)) {
                    $query->whereYear('dob', $dob);
                }
            } catch (\Exception $e) {
                // Optionally log the error
            }
        }
        $query->orderBy('name');
        $perPage = $request->query('per_page', 30);
        $students = $query->select([
            'id', 'name', 'student_code', 'class', 'dob', 'photo', 'school_id', 'updated_at'
        ])->paginate($perPage);
        
        return response()->json([
            'students' => $students->items(),
            'pagination' => [
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
                'per_page' => $students->perPage(),
                'total' => $students->total(),
                'has_more_pages' => $students->hasMorePages(),
            ],
            'permissions' => [
                'can_upload_image' => auth()->user()->can('upload student image'),
                'can_remove_image' => auth()->user()->can('remove student image'),
                'can_add_authority' => auth()->user()->can('add user') && auth()->user()->can('assign school'),
            ]
        ]);
    }

    public function uploadStudentPhoto(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:20480',
        ]);

        $student = Student::findOrFail($request->student_id);

        $file = $request->file('photo');
        $ext = $file->getClientOriginalExtension();
        $filename = $student->id . '.' . $ext;
        $path = public_path('uploads/images/students/');
        
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $file->move($path, $filename);

        $student->photo = $filename;
        $student->status = 1;
        $student->updated_by = auth()->id();
        $student->save();

        return response()->json([
            'message' => 'Photo uploaded successfully',
            'photo' => asset('uploads/images/students/' . $filename)
        ]);
    }

    public function saveStudent(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'class'        => 'required|string|max:50',
            'dob'          => 'nullable|date',
            'school_id'    => 'required|integer|exists:schools,id',
            'photo'        => 'nullable|string|max:255',
            'student_code' => 'nullable|string|max:50',
        ]);

        $student = new Student();
        $student->name        = $validated['name'];
        $student->class       = $validated['class'];
        $student->dob         = $validated['dob'] ?? null;
        $student->school_id   = $validated['school_id'];
        $student->student_code = $validated['student_code'] ?? null;
        $student->status      = 0;
        $student->created_by  = Auth::id();

        $student->save();
        
        if (!empty($validated['photo'])) {
            $originalPath = public_path($validated['photo']);
            if (file_exists($originalPath)) {
                $ext = pathinfo($originalPath, PATHINFO_EXTENSION);
                $newFileName = $student->id . '.' . $ext;
                $newPath = public_path('uploads/images/students/' . $newFileName);

                if (!file_exists(dirname($newPath))) {
                    mkdir(dirname($newPath), 0755, true);
                }

                if (rename($originalPath, $newPath)) {
                    $student->photo = $newFileName;
                    $student->status = 1;
                    $student->save();
                }
            }
        }

        $school = School::find($student->school_id);

        ActivityLogger::log('Add Student', 'Added student ' . ucwords($student->name) . ' to ' . $school->school_name);

        return response()->json([
            'message' => 'Student saved successfully!',
            'student' => $student,
        ], 201);
    }

    public function deletePhoto($studentId)
    {
        $student = Student::findOrFail($studentId);

        // Delete photo file from server
        if ($student->photo) {
            $photoPath = public_path('uploads/images/students/' . $student->photo);
            if (File::exists($photoPath)) {
                File::delete($photoPath);
            }
        }

        // Remove photo reference from DB
        $student->photo = null;
        $student->status = 0; // optional
        $student->updated_by = auth()->id();
        $student->save();

        return response()->json(['message' => 'Photo removed successfully.']);
    }
}
