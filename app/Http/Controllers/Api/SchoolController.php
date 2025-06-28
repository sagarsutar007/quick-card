<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

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
}

