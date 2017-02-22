<?php

namespace App\Packages\Facebook\Controllers;

use App\Controllers\Controller;
use App\Packages\Facebook\Models\FacebookAccount;
use App\Packages\Facebook\Models\FacebookGroup;
use App\Packages\Facebook\Repositories\FacebookAccountsRepository;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    /**
     * @var FacebookAccountsRepository
     */
    private $accountsRepository;

    public function __construct(FacebookAccountsRepository $accountsRepository)
    {
        $this->accountsRepository = $accountsRepository;
    }

    /**
     * Return all saved facebook accounts
     *
     * @return \Illuminate\Support\Collection
     */
    public function accounts()
    {
        return $this->accountsRepository->accounts();
    }

    /**
     * Show page with facebook accounts
     *
     * @return mixed
     */
    public function index()
    {
        $accounts = $this->accountsRepository->accounts();

        return view('facebook.accounts')->with(compact('accounts'));
    }

    /**
     * Add new account
     *
     * @param Request $request
     * @return bool|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse|static[]
     */
    public function store(Request $request)
    {
        // Redirect user to login to facebook
        return $this->accountsRepository->create();
    }

    public function token()
    {
        $this->accountsRepository->token();

        return redirect()->route('facebook-account.index');
    }

    public function groupsUpload(Request $request)
    {
        $this->validate($request, [
            'facebook_account_id' => 'required|exists:facebook_accounts,id',
            'html' => 'required'
        ]);

        $account = FacebookAccount::find($request->get('facebook_account_id'));
        $html = $request->get('html');

        $dom = new \simple_html_dom;

        $dom->load($html);

        $groups = [];

        foreach ($dom->find('._5fiw') as $node) {
            $link = $node->find('a')[0];

            $href = $link->getAttribute('href');
            $groupName = $link->text();

            preg_match('/([0-9])+/', $href, $matches);

            $groupId = $matches[0];

            $meta = $node->find('span.fcg')[0];

            preg_match('/[0-9]+/', str_replace(',', '', $meta->text()), $matches);

            $members = $matches[0];

            $groups[] = ['id' => $groupId, 'name' => $groupName, 'members' => $members];
        }
        
        $account->groups()->delete();

        foreach ($groups as $group) {
            FacebookGroup::create([
                'facebook_account_id' => $account->id,
                'groupId' => $group['id'],
                'name' => $group['name'],
                'members' => $group['members']
            ]);
        }

        return $account;
    }

    public function destroy(FacebookAccount $facebook_account)
    {
        if ($this->accountsRepository->destroy($facebook_account)) {
            return response('OK');
        }
        return response('Couldn\'t delete.', 401);
    }
}