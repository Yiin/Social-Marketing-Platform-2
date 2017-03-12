<?php

namespace App\Controllers;

use App\Constants\Role;
use App\Http\Requests\Client\CreateOrUpdateClient;
use App\Mail\SendUserPassword;
use App\Models\User;
use Auth;
use Illuminate\View\View;

/**
 * Class ClientsController
 * @package App\Controllers
 */
class ClientsController extends Controller
{
    /**
     * @return View
     */
    public function index()
    {
        $clients = Auth::user()->clients()->paginate(15);
        $client = null;

        return view('client.index')->with(compact('clients', 'client'));
    }

    /**
     * @param CreateOrUpdateClient $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function store(CreateOrUpdateClient $request)
    {
        $data = [
            'reseller_id' => Auth::user()->hasRole(Role::RESELLER) ? Auth::id() : null,
            'api_token' => str_random()
        ];
        $user = new User(array_merge($request->all(), $data));
        $user->password = bcrypt($password = $request->has('password') ? $request->get('password') : str_random());
        $user->save();

        $user->assignRole(Role::CLIENT);

        flash('Client created! Password: ' . $password);

        $email = $request->get('email');

        \Mail::to($email)->send(new SendUserPassword($email, $password));

        return redirect()->route('client.index');
    }

    /**
     * @param User $client
     * @return View
     */
    public function edit(User $client)
    {
        $clients = Auth::user()->clients()->paginate(15);

        return view('client.index')->with(compact('clients', 'client'));
    }

    /**
     * @param CreateOrUpdateClient $request
     * @param User $client
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(CreateOrUpdateClient $request, User $client)
    {
        $client->update($request->all());

        if ($request->has('password')) {
            $client->password = bcrypt($request->get('password'));
            $client->save();
        }

        flash('Client updated!');

        return redirect()->route('client.index');
    }

    /**
     * @param User $client
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(User $client)
    {
        $client->delete();

        return redirect()->route('client.index');
    }
}
