<?php

namespace App\Modules\Linkedin\Controllers;

use App\Controllers\Controller;
use App\Modules\Linkedin\Requests\QueuePosts;
use App\Modules\Linkedin\Services\ApiService;
use Illuminate\Http\Request;

class PostingController extends Controller
{
    public function post(QueuePosts $request, ApiService $apiService)
    {
        $queue = $apiService->post($request->all());

        return response($queue->id);
    }
}