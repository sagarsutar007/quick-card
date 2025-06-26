<?php

namespace App\Helpers;

use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function log($action, $description = null)
    {
        UserActivity::create([
            'user_id'    => Auth::id(),
            'action'     => $action,
            'description'=> $description,
            'ip_address' => Request::ip(),
        ]);
    }
}
