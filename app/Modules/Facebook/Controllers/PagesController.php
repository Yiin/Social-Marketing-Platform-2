<?php

namespace App\Modules\Facebook\Controllers;

use App\Modules\Facebook\Models\FacebookQueue;
use App\Modules\Facebook\Repositories\FacebookAccountsRepository;
use Auth;

class PagesController
{
    public function index(FacebookAccountsRepository $accountsRepository)
    {
        $clients = Auth::user()->clients()->get();
        $accounts = $accountsRepository->accounts();
        $templates = Auth::user()->templates;

        return view('facebook.index')->with(compact('clients', 'accounts', 'templates'));
    }

    public function stats(FacebookQueue $queue)
    {
        return view('facebook.stats', ['queue' => $queue]);
    }
}