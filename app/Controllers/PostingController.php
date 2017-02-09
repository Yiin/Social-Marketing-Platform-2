<?php

namespace App\Controllers;

use App\Http\Requests\PostToGooglePlus;
use App\Models\Account;
use App\Models\Client;
use App\Models\SocialMediaService;
use App\Models\Template;
use App\Services\CurlService;
use Illuminate\Http\Request;
use nxsAPI_GP;
use simple_html_dom;

class PostingController extends Controller
{
    public function view(SocialMediaService $socialMediaService)
    {
        $clients = Client::all();
        $accounts = Account::withGroups($socialMediaService->id);
        $templates = Template::all();
        $service = $socialMediaService;

        return view($socialMediaService->view)->with(compact('service', 'clients', 'accounts', 'templates'));
    }

    public function post(Request $request, SocialMediaService $socialMediaService)
    {
        $this->validate($request, $socialMediaService->validation['post']);

        $queue = $socialMediaService->impl()->post($request->all(), $socialMediaService);

        return response($queue->id);
    }
}
