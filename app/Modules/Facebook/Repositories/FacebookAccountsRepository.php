<?php

namespace App\Modules\Facebook\Repositories;

use App\Modules\Facebook\Models\FacebookAccount;
use App\Modules\Facebook\Services\ApiService;
use Auth;
use Illuminate\Support\Facades\Redirect;

/**
 * Class FacebookAccountsRepository
 * @package App\Modules\Facebook\Repositories
 */
class FacebookAccountsRepository
{
    /**
     * @var ApiService
     */
    private $apiService;

    /**
     * Initiates repository
     *
     * FacebookAccountsRepository constructor.
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
        return FacebookAccount::where('user_id', Auth::id())->with('groups')->get();
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
        $token = $this->apiService->getToken();
        $facebookUser = $this->apiService->getUser();

        $user = FacebookAccount::where('fbid', $facebookUser->id)->first();

        if (!$user) {
            $user = new FacebookAccount;
        }

        $user->fbid = $facebookUser->id;
        $user->name = $facebookUser->name;
        $user->access_token = $token;
        $user->user_id = Auth::id();

        $user->save();

        return $user;
    }

    /**
     * @param FacebookAccount $account
     * @param $data
     * @return bool|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(FacebookAccount $account, $data)
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
     * @param FacebookAccount $account
     * @return bool|null
     */
    public function destroy(FacebookAccount $account)
    {
        return $account->delete();
    }
}