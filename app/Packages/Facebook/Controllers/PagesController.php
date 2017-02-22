<?php

namespace App\Packages\Facebook\Controllers;

use App\Models\Client;
use App\Models\Template;
use App\Packages\Facebook\Models\FacebookQueue;
use App\Packages\Facebook\Repositories\FacebookAccountsRepository;

class PagesController
{
    public function index(FacebookAccountsRepository $accountsRepository)
    {
        $clients = Client::all();
        $accounts = $accountsRepository->accounts();
        $templates = Template::all();

        return view('facebook.index')->with(compact('clients', 'accounts', 'templates'));
    }

    public function stats(FacebookQueue $queue)
    {
        return view('facebook.stats', ['queue' => $queue]);
    }
}