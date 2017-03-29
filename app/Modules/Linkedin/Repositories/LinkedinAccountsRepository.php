<?php

namespace App\Modules\Linkedin\Repositories;

use App\Modules\Linkedin\Events\Authenticated;
use App\Modules\Linkedin\Models\LinkedinAccount;
use App\Modules\Linkedin\Models\LinkedinGroup;
use App\Modules\Linkedin\Services\ApiService;
use Auth;
use Cache;
use Illuminate\Http\Request;

/**
 * Class LinkedinAccountsRepository
 * @package App\Modules\Linkedin\Repositories
 */
class LinkedinAccountsRepository
{
    /**
     * @var ApiService
     */
    private $apiService;

    /**
     * Initiates repository
     *
     * LinkedinAccountsRepository constructor.
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
        return LinkedinAccount::where('user_id', Auth::id())->with('groups')->get();
    }


    /**
     * Fetch and save groups that user is member of
     *
     * @param LinkedinAccount $account
     * @param bool $useLastSession
     */
    public function fetchAndUpdateGroups(LinkedinAccount $account, $useLastSession = false)
    {
        $groups = $this->apiService->groups($account, $useLastSession);

        $account->groups()->delete();

        foreach ($groups as $group) {
            LinkedinGroup::create([
                'linkedin_account_id' => $account->id,
                'groupId' => $group->id,
                'name' => $group->name,
                'members' => $group->members
            ]);
        }
    }


    /**
     * @param $accountData
     * @return LinkedinAccount
     * @throws \Exception
     */
    public function create($accountData)
    {
        if (LinkedinAccount::where('email', $accountData['email'])->exists()) {
            // Account already exists
            return null;
        }

        $account = new LinkedinAccount($accountData);

        // check if account credentials are correct
        $response = $this->apiService->check($account);

        // authentication failed
        if (!$response->is_authenticated) {
            throw new \Exception('Login failed.');
        }

        $account->user_id = Auth::id();
        $account->save();

        $this->fetchAndUpdateGroups($account, true);

        $account->groups;

        return $account;
    }

    /**
     * @param LinkedinAccount $account
     * @param $data
     * @return bool|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(LinkedinAccount $account, $data)
    {
        $account->fill($data);

        // check if account credentials are correct
        $response = $this->apiService->check($account);

        // authentication failed
        if (!$response->is_authenticated) {
            throw new \Exception('Login failed.');
        }

        // credentials are ok, update the account
        $success = $account->save();

        return $success;
    }

    /**
     * @param LinkedinAccount $account
     * @return bool|null
     */
    public function destroy(LinkedinAccount $account)
    {
        return $account->delete();
    }
}