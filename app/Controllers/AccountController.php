<?php

namespace App\Controllers;

use App\Http\Requests\Account\CreateOrUpdateAccount;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function accounts($social_media_service_id = null)
    {
        if ($social_media_service_id) {
            return Account::withGroups($social_media_service_id);
        }
        return Account::all();
    }

    public function index()
    {
        $accounts = Account::all();

        return view('accounts')->with(compact('accounts'));
    }

    public function store(CreateOrUpdateAccount $request)
    {
        Account::create($request->only((new Account)->getFillable()));

        return Account::all();
    }

    public function update(CreateOrUpdateAccount $request, Account $account)
    {
        $account->update($request->only($account->getFillable()));

        return response('OK');
    }

    public function destroy(Account $account)
    {
        $account->delete();

        return response('OK');
    }
}
