<?php

namespace App\Packages\GooglePlus\Controllers;

use App\Models\Client;
use App\Models\Template;
use App\Packages\GooglePlus\Models\GoogleQueue;
use App\Packages\GooglePlus\Repositories\GoogleAccountsRepository;

class PagesController
{
    public function index(GoogleAccountsRepository $accountsRepository)
    {
        $clients = Client::all();
        $accounts = $accountsRepository->accounts();
        $templates = Template::all();

        return view('google.index')->with(compact('clients', 'accounts', 'templates'));
    }

    public function stats(GoogleQueue $queue)
    {
        return view('google.stats', ['queue' => $queue]);
    }
}