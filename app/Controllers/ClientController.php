<?php

namespace App\Controllers;

use App\Http\Requests\Client\CreateOrUpdateClient;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();

        return view('clients')->with(compact('clients'));
    }

    public function store(CreateOrUpdateClient $request)
    {
        Client::create($request->only((new Client)->getFillable()));

        return Client::all();
    }

    public function update(CreateOrUpdateClient $request, Client $client)
    {
        $client->update($request->only($client->getFillable()));

        return response('OK');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return response('OK');
    }
}
