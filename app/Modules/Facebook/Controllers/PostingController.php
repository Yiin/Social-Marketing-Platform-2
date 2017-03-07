<?php

namespace App\Modules\Facebook\Controllers;

use App\Controllers\Controller;
use App\Models\Client;
use App\Models\Template;
use App\Modules\Facebook\Repositories\FacebookAccountsRepository;
use App\Modules\Facebook\Services\ApiService;
use Illuminate\Http\Request;

class PostingController extends Controller
{
    public function post(Request $request, ApiService $apiService)
    {
        $queue = $apiService->post($request->all());

        return response($queue->id);
    }
}