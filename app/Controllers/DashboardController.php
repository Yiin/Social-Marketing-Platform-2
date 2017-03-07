<?php

namespace App\Controllers;

use App\Modules\Errors\Models\ErrorLog;
use App\Models\User;
use Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $blocks = [];

        if (Auth::user()->hasPermissionTo(User::VIEW_ERRORS_LOG)) {
            $block['errorsLog'] = ErrorLog::paginate(15);
        }

        return view('dashboard', compact('blocks'));
    }

    public function profile()
    {
        return view('user-profile');
    }
}