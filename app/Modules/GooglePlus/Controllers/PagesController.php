<?php

namespace App\Modules\GooglePlus\Controllers;

use App\Models\Client;
use App\Models\Template;
use App\Modules\GooglePlus\Models\GoogleQueue;
use App\Modules\GooglePlus\Repositories\GoogleAccountsRepository;
use Auth;

class PagesController
{
    public function index(GoogleAccountsRepository $accountsRepository)
    {
        $clients = Auth::user()->clients()->get();
        $accounts = $accountsRepository->accounts();
        $templates = Auth::user()->templates;

        return view('google.index')->with(compact('clients', 'accounts', 'templates'));
    }

    public function stats(GoogleQueue $queue)
    {
        return view('google.stats', ['queue' => $queue]);
    }
}