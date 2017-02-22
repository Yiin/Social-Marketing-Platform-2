<?php

namespace App\Packages\GooglePlus\Repositories;

use App\Packages\GooglePlus\Models\GoogleAccount;
use App\Packages\GooglePlus\Services\ApiService;

/**
 * Class GoogleAccountsRepository
 * @package App\Packages\GooglePlus\Repositories
 */
class GoogleAccountsRepository
{
    /**
     * @var ApiService
     */
    private $apiService;

    /**
     * Initiates repository
     *
     * GoogleAccountsRepository constructor.
     * @param ApiService $apiService
     */
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Get the lsit of accounts
     *
     * @return \Illuminate\Support\Collection
     */
    public function accounts()
    {
        return GoogleAccount::all()->map(function (GoogleAccount $account) {
            $account->groups = $this->apiService->login($account) ? $this->apiService->groups() : [];

            return $account;
        });
    }

    /**
     * @param $accountData
     * @return GoogleAccount
     * @throws \Exception
     */
    public function create($accountData)
    {
        if (GoogleAccount::where('username', $accountData['username'])->exists()) {
            throw new \Exception('Account exists');
        }
        $account = new GoogleAccount($accountData);

        // check if account credentials are correct
        $result = $this->apiService->check($account);

        if (!$result) {
            throw new \Exception('Login failed.');
        }

        // everything's ok, save account
        $account->save();

        return $account;
    }

    /**
     * @param GoogleAccount $account
     * @param $data
     * @return bool|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(GoogleAccount $account, $data)
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
     * @param GoogleAccount $account
     * @return bool|null
     */
    public function destroy(GoogleAccount $account)
    {
        return $account->delete();
    }
}