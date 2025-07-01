<?php

namespace App\Http\Controllers;

use App\Models\UserActivity;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function loggedUserData(Request $request)
    {
        if ($request->ajax()) {
            $userId = $request->query('user_id') ?? Auth::id();
            
            $data = UserActivity::with('user')
                ->where('user_id', $userId);

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y h:i A') : '';
                })
                ->make(true);
        }
    }
}
