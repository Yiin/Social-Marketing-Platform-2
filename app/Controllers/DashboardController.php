<?php
/**
 * Created by PhpStorm.
 * User: Stanislovas
 * Date: 2017-02-07
 * Time: 20:39
 */

namespace App\Controllers;


use App\Models\User;

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