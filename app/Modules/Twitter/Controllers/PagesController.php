<?php

namespace App\Modules\Twitter\Controllers;

use App\Controllers\Controller;
use App\Modules\Twitter\Models\TwitterQueue;
use App\Modules\Twitter\Repositories\TwitterAccountsRepository;
use Auth;

class PagesController extends Controller
{
    public function index(TwitterAccountsRepository $accountsRepository)
    {
        $clients = Auth::user()->clients()->get();
        $accounts = $accountsRepository->accounts();
        $templates = Auth::user()->templates;

        return view('twitter.index')->with(compact('clients', 'accounts', 'templates'));
    }

    public function stats(TwitterQueue $queue)
    {
        return view('twitter.stats', ['queue' => $queue]);
    }
}