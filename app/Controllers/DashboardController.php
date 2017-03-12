<?php

namespace App\Controllers;

use App\Modules\Errors\Models\ErrorLog;
use App\Models\User;
use Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function profile()
    {
        return view('user-profile');
    }
}