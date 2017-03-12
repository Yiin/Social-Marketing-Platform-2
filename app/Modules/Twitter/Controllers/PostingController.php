<?php

namespace App\Modules\Twitter\Controllers;

use App\Controllers\Controller;
use App\Modules\Twitter\Requests\QueueTweets;
use App\Modules\Twitter\Services\ApiService;

class PostingController extends Controller
{
    public function post(QueueTweets $request, ApiService $apiService)
    {
        $queue = $apiService->tweet($request->all());

        return response($queue->id);
    }
}