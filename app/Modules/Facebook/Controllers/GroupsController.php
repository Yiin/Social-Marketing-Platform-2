<?php

namespace App\Modules\Facebook\Controllers;

use App\Controllers\Controller;
use App\Modules\Facebook\Models\FacebookAccount;
use App\Modules\Facebook\Models\FacebookGroup;
use App\Modules\Facebook\Repositories\FacebookAccountsRepository;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    public function index()
    {
        $groups = FacebookGroup::all();

        return view('facebook.groups', compact('groups'));
    }
}