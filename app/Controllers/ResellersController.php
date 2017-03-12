<?php

namespace App\Controllers;

use App\Constants\Role;
use App\Http\Requests\Reseller\CreateOrUpdateReseller;
use App\Mail\SendUserPassword;
use App\Models\User;
use Auth;
use Illuminate\View\View;

class ResellersController extends Controller
{
    /**
     * @return View
     */
    public function index()
    {
        $resellers = User::role(Role::RESELLER)->paginate(15);

        return view('reseller.index')->with(compact('resellers'));
    }

    public function clients(User $reseller)
    {
        $clients = $reseller->clients()->paginate(15);

        return view('client.index', compact('clients', 'reseller'));
    }

    /**
     * @param CreateOrUpdateReseller $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function store(CreateOrUpdateReseller $request)
    {
        $data = [
            'api_token' => str_random()
        ];
        $user = new User(array_merge($request->all(), $data));
        $user->password = bcrypt($password = $request->has('password') ? $request->get('password') : str_random());
        $user->save();

        $user->assignRole(Role::RESELLER);
        $user->update(['reseller_id' => $user->id]);

        flash('Reseller created! Password: ' . $password);

        $email = $request->get('email');

        \Mail::to($email)->send(new SendUserPassword($email, $password));

        return redirect()->route('reseller.index');
    }

    /**
     * @param User $reseller
     * @return View
     */
    public function edit(User $reseller)
    {
        $resellers = User::role(Role::RESELLER)->paginate(15);

        return view('reseller.index')->with(compact('resellers', 'reseller'));
    }

    /**
     * @param CreateOrUpdateReseller $request
     * @param User $reseller
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(CreateOrUpdateReseller $request, User $reseller)
    {
        $reseller->update($request->all());

        if ($request->has('password')) {
            $reseller->password = bcrypt($request->get('password'));
            $reseller->save();
        }

        flash('Reseller updated!');

        return redirect()->route('reseller.index');
    }

    /**
     * @param User $reseller
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(User $reseller)
    {
        $reseller->delete();

        return redirect()->route('reseller.index');
    }
}
