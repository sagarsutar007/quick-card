<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q');

        $query = School::select('id', 'school_name');

        if ($search) {
            $query->where('school_name', 'like', '%' . $search . '%');
        }

        return response()->json($query->limit(20)->get());
    }

    public function search(Request $request)
    {
        $search = $request->get('q');

        $query = School::select(
                'schools.id',
                'schools.school_name',
                'schools.school_code',
                'schools.udise_no',
                'schools.school_address',
                'schools.description',
                'districts.name as district_name',
                'blocks.name as block_name',
                'clusters.name as cluster_name',
                DB::raw('(SELECT COUNT(*) FROM students WHERE students.school_id = schools.id) as students_count'),
                DB::raw('(SELECT COUNT(*) FROM students WHERE students.school_id = schools.id AND students.photo IS NOT NULL AND students.photo != "") as photo_count'),
                DB::raw('(SELECT COUNT(*) FROM students WHERE students.school_id = schools.id AND (students.photo IS NULL OR students.photo = "")) as no_photo_count')
            )
            ->leftJoin('districts', 'schools.district_id', '=', 'districts.id')
            ->leftJoin('blocks', 'schools.block_id', '=', 'blocks.id')
            ->leftJoin('clusters', 'schools.cluster_id', '=', 'clusters.id');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('schools.school_name', 'like', '%' . $search . '%')
                ->orWhere('schools.school_code', 'like', '%' . $search . '%')
                ->orWhere('schools.udise_no', 'like', '%' . $search . '%')
                ->orWhere('districts.name', 'like', '%' . $search . '%')
                ->orWhere('blocks.name', 'like', '%' . $search . '%')
                ->orWhere('clusters.name', 'like', '%' . $search . '%');
            });
        }

        return response()->json([
            'schools' => $query->limit(50)->get()
        ]);
    }


    public function getUserSchools(Request $request)
    {
        $user = $request->user();
        $role = $user->getRoleNames()->first();

        $schools = School::select(
                'schools.id',
                'schools.school_name',
                'schools.school_code',
                'schools.udise_no',
                'schools.school_address',
                'schools.description',                
                'districts.name as district_name',
                'blocks.name as block_name',
                'clusters.name as cluster_name',
                DB::raw('(SELECT COUNT(*) FROM students WHERE students.school_id = schools.id) as students_count'),
                DB::raw('(SELECT COUNT(*) FROM students WHERE students.school_id = schools.id AND students.photo IS NOT NULL AND students.photo != "") as photo_count'),
                DB::raw('(SELECT COUNT(*) FROM students WHERE students.school_id = schools.id AND (students.photo IS NULL OR students.photo = "")) as no_photo_count')
            )
            ->leftJoin('districts', 'schools.district_id', '=', 'districts.id')
            ->leftJoin('blocks', 'schools.block_id', '=', 'blocks.id')
            ->leftJoin('clusters', 'schools.cluster_id', '=', 'clusters.id');

        if (in_array($role, ['authority', 'custom'])) {
            if ($user->school_id) {
                $schools->where('schools.id', $user->school_id);
            } else {
                return response()->json([
                    'schools' => []
                ]);
            }
        } elseif ($role === 'staff') {
            $schoolIds = $user->schools()->pluck('schools.id');
            $schools->whereIn('schools.id', $schoolIds);
        } else {
            return response()->json([
                'message' => 'Access denied or not supported for this role.'
            ], 403);
        }

        return response()->json([
            'schools' => $schools->get()
        ]);
    }
}

