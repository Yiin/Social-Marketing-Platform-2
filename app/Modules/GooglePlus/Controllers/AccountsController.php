<?php

namespace App\Modules\GooglePlus\Controllers;

use App\Controllers\Controller;
use App\Modules\GooglePlus\Models\GoogleAccount;
use App\Modules\GooglePlus\Repositories\GoogleAccountsRepository;
use App\Modules\GooglePlus\Requests\CreateOrUpdateGoogleAccount;

class AccountsController extends Controller
{
    /**
     * @var GoogleAccountsRepository
     */
    private $accountsRepository;

    public function __construct(GoogleAccountsRepository $accountsRepository)
    {
        $this->accountsRepository = $accountsRepository;
    }

    public function accounts()
    {
        return $this->accountsRepository->accounts();
    }

    public function index()
    {
        $accounts = GoogleAccount::all();

        return view('google.accounts')->with(compact('accounts'));
    }

    /**
     * @param CreateOrUpdateGoogleAccount $request
     * @return bool|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse|static[]
     */
    public function store(CreateOrUpdateGoogleAccount $request)
    {
        try {
            $this->accountsRepository->create($request->all());
        } catch (\Exception $e) {
            return response()->json(['username' => [$e->getMessage()]], 401);
        }

        return GoogleAccount::all();
    }

    public function update(CreateOrUpdateGoogleAccount $request, GoogleAccount $google_account)
    {
        try {
            $this->accountsRepository->update($google_account, $request->all());
        } catch (\Exception $e) {
            return response()->json(['username' => [$e->getMessage()]], 401);
        }
        return response('OK');
    }

    public function destroy(GoogleAccount $google_account)
    {
        if ($this->accountsRepository->destroy($google_account)) {
            return response('OK');
        }
        return response('Couldn\'t delete.', 401);
    }
}