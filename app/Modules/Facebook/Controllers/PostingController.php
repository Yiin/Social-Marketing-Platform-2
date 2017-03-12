<?php

namespace App\Modules\Facebook\Controllers;

use App\Controllers\Controller;
use App\Modules\Facebook\Requests\QueuePosts;
use App\Modules\Facebook\Services\ApiService;
use Illuminate\Http\Request;

class PostingController extends Controller
{
    public function post(QueuePosts $request, ApiService $apiService)
    {
        $queue = $apiService->post($request->all());

        return response($queue->id);
    }
}