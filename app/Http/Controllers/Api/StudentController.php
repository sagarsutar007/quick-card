<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\School;

class StudentController extends Controller
{
    public function getSchoolStudents(Request $request, $schoolId)
    {
        $query = Student::where('school_id', $schoolId);
            
        if ($status = $request->query('status')) {
            if (strtolower($status) === 'uploaded') {
                $query->where('status', 1);
            } elseif (strtolower($status) === 'not uploaded') {
                $query->where('status', 0);
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
                    $query->whereRaw('DAY(dob) = ? AND MONTH(dob) = ?', [$day, $month]);
                } elseif (preg_match('/^\d{2}\/\d{4}$/', $dob)) {
                    [$month, $year] = explode('/', $dob);
                    $query->whereRaw('MONTH(dob) = ? AND YEAR(dob) = ?', [$month, $year]);
                } elseif (preg_match('/^\d{4}$/', $dob)) {
                    $query->whereYear('dob', $dob);
                }
            } catch (\Exception $e) {
                
            }
        }
        
        $students = $query->paginate($request->get('per_page', 20));

        return response()->json([
            'data' => $students->items(),
            'current_page' => $students->currentPage(),
            'last_page' => $students->lastPage(),
            'total' => $students->total(),
        ]);
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
}
