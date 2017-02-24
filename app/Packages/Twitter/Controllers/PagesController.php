<?php

namespace App\Packages\Twitter\Controllers;

use App\Controllers\Controller;
use App\Models\Client;
use App\Models\Template;
use App\Packages\Twitter\Models\TwitterQueue;
use App\Packages\Twitter\Repositories\TwitterAccountsRepository;

class PagesController extends Controller
{
    public function index(TwitterAccountsRepository $accountsRepository)
    {
        $clients = Client::all();
        $accounts = $accountsRepository->accounts();
        $templates = Template::all();

        return view('twitter.index')->with(compact('clients', 'accounts', 'templates'));
    }

    public function stats(TwitterQueue $queue)
    {
        return view('twitter.stats', ['queue' => $queue]);
    }
}