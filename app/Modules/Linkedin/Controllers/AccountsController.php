<?php

namespace App\Modules\Linkedin\Controllers;

use App\Controllers\Controller;
use App\Modules\Linkedin\Models\LinkedinAccount;
use App\Modules\Linkedin\Repositories\LinkedinAccountsRepository;
use App\Modules\Linkedin\Requests\CreateOrUpdateLinkedinAccount;

class AccountsController extends Controller
{
    /**
     * @var LinkedinAccountsRepository
     */
    private $accountsRepository;

    public function __construct(LinkedinAccountsRepository $accountsRepository)
    {
        $this->accountsRepository = $accountsRepository;
    }

    public function accounts()
    {
        return $this->accountsRepository->accounts();
    }

    public function index()
    {
        $accounts = $this->accountsRepository->accounts();

        return view('linkedin.accounts')->with(compact('accounts'));
    }

    public function fetchAndUpdateGroups(LinkedinAccount $linkedin_account)
    {
        $this->accountsRepository->fetchAndUpdateGroups($linkedin_account);

        return $linkedin_account->groups;
    }

    public function store(CreateOrUpdateLinkedinAccount $request)
    {
        try {
            $this->accountsRepository->create($request->all());
        } catch (\Exception $e) {
            return response()->json(['email' => [$e->getMessage()]], 401);
        }

        return LinkedinAccount::all();
    }

    public function update(CreateOrUpdateLinkedinAccount $request, LinkedinAccount $linkedin_account)
    {
        try {
            $this->accountsRepository->update($linkedin_account, $request->all());
        } catch (\Exception $e) {
            return response()->json(['email' => [$e->getMessage()]], 401);
        }
        return response('OK');
    }

    public function destroy(LinkedinAccount $linkedin_account)
    {
        if ($this->accountsRepository->destroy($linkedin_account)) {
            return response('OK');
        }
        return response("Couldn't delete.", 401);
    }
}
