<?php

namespace App\Modules\Linkedin\Controllers;

use App\Modules\Linkedin\Models\LinkedinQueue;
use App\Modules\Linkedin\Repositories\LinkedinAccountsRepository;
use Auth;

class PagesController
{
    public function index(LinkedinAccountsRepository $accountsRepository)
    {
        $clients = Auth::user()->clients()->get();
        $accounts = $accountsRepository->accounts();
        $templates = Auth::user()->templates;

        return view('linkedin.index')->with(compact('clients', 'accounts', 'templates'));
    }

    public function stats(LinkedinQueue $queue)
    {
        return view('linkedin.stats', ['queue' => $queue]);
    }
}
//