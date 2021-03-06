<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;
use Illuminate\Support\Facades\Response;
use App\Events\ClientAdded;
use Auth;

class ClientController extends Controller
{

    public function index()
    {
        $clients = Client::all();
        $clients->filter->typeclient;
        $clients->filter->tickets;
        $clients->filter->contacts->filter->tickets;
        $clients->filter->adresses;
        $clients->filter->users;
        $clients->filter->responsable(Auth::user());
        return $clients;
    }

    public function store(ClientRequest $request)
    {
        $client = Client::create($request->toArray());
        if($client){
            $client->users()->sync($request->users);
            event(new ClientAdded($client));
        }

    return Response::json(['message' => 'Client bien ajouté'], 200);
    }

    public function show($id)
    {
        $client = Client::findOrfail($id);
        $client->typeclient;
        $client->tickets;
        $client->contacts->filter->tickets;
        $client->adresses;
        $client->users;
        return Response::json($client, 200);
    }

    public function update(ClientRequest $request, $id)
    {
        $client = Client::findOrfail($id);
        $client->update($request->toArray());
        $client->users()->sync($request->users);

    return Response::json(['message' => 'Client bien mis à jour'], 200);
    }

    public function destroy($id)
    {
        Client::destroy($id);
        return Response::json(['message' => 'Client bien supprimé'], 200);
    }
}
