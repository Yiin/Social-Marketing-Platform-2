<?php

namespace App\Modules\GooglePlus\Controllers;

use App\Controllers\Controller;
use App\Modules\GooglePlus\Requests\QueuePosts;
use App\Modules\GooglePlus\Services\ApiService;

class PostingController extends Controller
{
    public function post(QueuePosts $request, ApiService $apiService)
    {
        $queue = $apiService->post($request->all());

        return response($queue->id);
    }
}