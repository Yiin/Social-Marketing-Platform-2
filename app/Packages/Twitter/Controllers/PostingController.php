<?php

namespace App\Packages\Twitter\Controllers;

use App\Controllers\Controller;
use App\Packages\Twitter\Services\ApiService;
use Illuminate\Http\Request;

class PostingController extends Controller
{
    public function post(Request $request, ApiService $apiService)
    {
        $queue = $apiService->tweet($request->all());

        return response($queue->id);
    }
}