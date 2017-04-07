<?php

namespace App\Modules\Linkedin\Controllers;

use App\Controllers\Controller;
use App\Modules\Linkedin\Exceptions\AuthenticationException;
use App\Modules\Linkedin\Exceptions\AuthorizationException;
use App\Modules\Linkedin\Models\LinkedinAccount;
use App\Modules\Linkedin\Repositories\LinkedinAccountsRepository;
use App\Modules\Linkedin\Requests\CreateOrUpdateLinkedinAccount;
use App\Modules\Linkedin\Requests\UnlockLinkedinAccount;

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
        } catch (AuthenticationException $e) {
            return response()->json(['email' => [$e->getMessage()]], \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => 'locked']);
        }

        return response()->json(['status' => 'authenticated', 'accounts' => $this->accountsRepository->accounts()]);
    }

    public function unlock(UnlockLinkedinAccount $request)
    {
        $account = $this->accountsRepository->unlock($request->only(['email', 'password']), $request->get('code'));

        if ($account) {
            return $this->accountsRepository->accounts();
        }
        return response()->json(['code' => ['Invalid code.']], \Symfony\Component\HttpFoundation\Response::HTTP_LOCKED);
    }

    public function update(CreateOrUpdateLinkedinAccount $request, LinkedinAccount $linkedin_account)
    {
        try {
            $this->accountsRepository->update($linkedin_account, $request->all());
        } catch (\Exception $e) {
            return response()->json(['email' => [$e->getMessage()]], \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
        }
        return response('OK');
    }

    public function destroy(LinkedinAccount $linkedin_account)
    {
        if ($this->accountsRepository->destroy($linkedin_account)) {
            return response('OK');
        }
        return response("Couldn't delete.", \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
    }
}
