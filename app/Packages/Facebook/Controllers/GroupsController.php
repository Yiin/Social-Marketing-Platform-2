<?php

namespace App\Packages\Facebook\Controllers;

use App\Controllers\Controller;
use App\Packages\Facebook\Models\FacebookAccount;
use App\Packages\Facebook\Models\FacebookGroup;
use App\Packages\Facebook\Repositories\FacebookAccountsRepository;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    public function index()
    {
        $groups = FacebookGroup::all();

        return view('facebook.groups', compact('groups'));
    }
}