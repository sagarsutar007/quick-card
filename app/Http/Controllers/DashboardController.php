<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function showDashboard(Request $request)
    {
        // Return the dashboard view
        return view('dashboard');
    }
}
