<?php

namespace App\Packages\Facebook\Controllers;

use App\Models\Client;
use App\Models\Template;
use App\Packages\Facebook\Models\FacebookQueue;
use App\Packages\Facebook\Repositories\FacebookAccountsRepository;
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