<?php

namespace App\Packages\Facebook\Controllers;

use App\Controllers\Controller;
use App\Models\Client;
use App\Models\Template;
use App\Packages\Facebook\Repositories\FacebookAccountsRepository;
use App\Packages\Facebook\Services\ApiService;
use Illuminate\Http\Request;

class PostingController extends Controller
{
    public function view(FacebookAccountsRepository $accountsRepository)
    {
        $clients = Client::all();
        $accounts = $accountsRepository->accounts();
        $templates = Template::all();

        return view('google-plus')->with(compact('service', 'clients', 'accounts', 'templates'));
    }

    public function post(Request $request, ApiService $apiService)
    {
        $queue = $apiService->post($request->all());

        return response($queue->id);
    }
}