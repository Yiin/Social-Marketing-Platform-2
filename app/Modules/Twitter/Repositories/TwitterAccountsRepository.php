<?php

namespace App\Modules\Twitter\Repositories;

use App\Modules\Twitter\Models\TwitterAccount;
use App\Modules\Twitter\Services\ApiService;
use Auth;
use Illuminate\Support\Facades\Redirect;

/**
 * Class TwitterAccountsRepository
 * @package App\Modules\Twitter\Repositories
 */
class TwitterAccountsRepository
{
    /**
     * @var ApiService
     */
    private $apiService;

    /**
     * Initiates repository
     *
     * TwitterAccountsRepository constructor.
     * @param ApiService $apiService
     */
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Get the list of accounts
     *
     * @return \Illuminate\Support\Collection
     */
    public function accounts()
    {
        return TwitterAccount::where('user_id', Auth::id())->get();
    }

    /**
     * @return Redirect|bool
     * @throws \Exception
     */
    public function create()
    {
        return $this->apiService->authenticate();
    }

    public function token()
    {
        try {
            $token = $this->apiService->token();
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return null;
        }
        $account = TwitterAccount::where('twitter_id', $token['user_id'])->first();

        if (!$account) {
            $account = new TwitterAccount;
            $account->twitter_id = $token['user_id'];
        }

        $account->name = $token['screen_name'];
        $account->oauth_token = $token['oauth_token'];
        $account->oauth_secret = $token['oauth_token_secret'];
        $account->user_id = Auth::id();

        $account->save();

        return $account;
    }

    /**
     * @param TwitterAccount $account
     * @param $data
     * @return bool|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(TwitterAccount $account, $data)
    {
        $account->fill($data);

        // check if account credentials are correct
        $result = $this->apiService->check($account);

        if (!$result) {
            throw new \Exception('Login failed.');
        }

        // credentials are  ok, update the account
        $success = $account->save();

        return $success;
    }

    /**
     * @param TwitterAccount $account
     * @return bool|null
     */
    public function destroy(TwitterAccount $account)
    {
        return $account->delete();
    }
}