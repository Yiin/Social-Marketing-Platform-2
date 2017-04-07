<?php

namespace App\Modules\Linkedin\Repositories;

use App\Modules\Linkedin\Events\Authenticated;
use App\Modules\Linkedin\Exceptions\AuthenticationException;
use App\Modules\Linkedin\Exceptions\AuthorizationException;
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
     */
    public function fetchAndUpdateGroups(LinkedinAccount $account)
    {
        $response = $this->apiService->groups($account);

        $account->groups()->delete();

        foreach ($response->groups as $group) {
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

        switch ($response->status) {
            case 'ok':
                $account->user_id = Auth::id();
                $account->save();

                $account->groups;

                return $account;
            case 'locked':
                throw new AuthorizationException('Sign-In Verification required.');
                break;
            case 'unauthorized':
                throw new AuthenticationException('Login failed.');
                break;
        }
    }

    public function unlock($accountData, $code)
    {
        $account = new LinkedinAccount($accountData);

        // check if account credentials are correct
        $response = $this->apiService->unlock($account, $code);

        switch ($response->status) {
            case 'ok':
                $account->user_id = Auth::id();
                $account->save();

                $account->groups;

                return $account;
            case 'locked':
                return null;
        }
    }

    /**
     * @param LinkedinAccount $account
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function update(LinkedinAccount $account, $data)
    {
        $account->fill($data);

        // check if account credentials are correct
        $response = $this->apiService->check($account);

        switch ($response->status) {
            case 'ok':
                $account->user_id = Auth::id();
                $account->save();

                $account->groups;

                return $account;

            case 'locked':
                throw new AuthorizationException('Sign-In Verification required.');
                break;

            case 'unauthorized':
                throw new AuthenticationException('Login failed.');
                break;
        }
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