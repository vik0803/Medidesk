<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserroleRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $users->filter->roles;
        $users->filter->clients;
        $users->filter->tickets;
        $users->filter->emails;
        $users->filter->appels;
        $users->filter->roles;

    return $users;
    }

    public function store(UserRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

    return Response::json(['message' => 'User bien ajouté'], 200);
    }

    public function attachRole(UserroleRequest $request)
    {
        $user = User::findOrfail($request->user_id);
        $user->detachAllRoles();
        $user->attachRole($request->role_id);

    return Response::json(['message' => 'Role bien attaché à l\'utilisateur'], 200);
    }

    public function show($id)
    {
        $user = User::findOrfail($id);
        $user->role;
        $user->clients;
        $user->tickets;
        $user->emails;
        $user->appels;
        $user->roles;

    return Response::json($user, 200);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::findOrfail($id);
        if ($request['password'] != '') {
          $user->update([
              'name' => $request['name'],
              'email' => $request['email'],
              'password' => bcrypt($request['password']),
          ]);
        }else {
          $user->update([
              'name' => $request['name'],
              'email' => $request['email'],
          ]);
        }

    return Response::json(['message' => 'User bien mis à jour'], 200);
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $user->update([
            'password' => bcrypt($request['password']),
        ]);
        Auth::logout();
    return Response::json(['message' => 'Mot de passe bien mis à jour'], 200);
    }

    public function destroy($id)
    {
        User::destroy($id);

    return Response::json(['message' => 'User bien supprimé'], 200);
    }
}
