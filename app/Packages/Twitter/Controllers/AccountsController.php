<?php

namespace App\Packages\Twitter\Controllers;

use App\Controllers\Controller;
use App\Packages\Twitter\Models\TwitterAccount;
use App\Packages\Twitter\Repositories\TwitterAccountsRepository;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    /**
     * @var TwitterAccountsRepository
     */
    private $accountsRepository;

    public function __construct(TwitterAccountsRepository $accountsRepository)
    {
        $this->accountsRepository = $accountsRepository;
    }

    /**
     * Return all saved twitter accounts
     *
     * @return \Illuminate\Support\Collection
     */
    public function accounts()
    {
        return $this->accountsRepository->accounts();
    }

    /**
     * Show page with twitter accounts
     *
     * @return mixed
     */
    public function index()
    {
        $accounts = $this->accountsRepository->accounts();

        return view('twitter.accounts')->with(compact('accounts'));
    }

    /**
     * Add new account
     *
     * @param Request $request
     * @return bool|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse|static[]
     */
    public function store(Request $request)
    {
        // Redirect user to login to twitter
        return $this->accountsRepository->create();
    }

    public function token()
    {
        $this->accountsRepository->token();

        return redirect()->route('twitter-account.index');
    }

    public function destroy(TwitterAccount $twitter_account)
    {
        if ($this->accountsRepository->destroy($twitter_account)) {
            return response('OK');
        }
        return response('Couldn\'t delete.', 401);
    }
}