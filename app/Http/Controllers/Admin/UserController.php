<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Requests\StoreUser as StoreUserRequest;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class UserController extends Controller
{
  public function __construct(){
    $this->authorizeResource(User::class, 'user');
  }

  public function index()
  {
    $users = User::all();
    return Inertia::render('Users', compact('users'));
  }

  public function store(StoreUserRequest $request)
  {
    $user = User::create([
      'name'     => $request->name,
      'email'    => $request->email,
      'password' => bcrypt($request->password)
    ]);

    return new UserResource( $user );
  }


  public function update(Request $request, User $user)
  {
    $user->update([
      'name'     => $request->name  ?: $user->name,
      'email'    => $request->email ?: $user->email,
      'password' => $request->password ? bcrypt($request->password) : $user->password
    ]);

    return new UserResource( $user );
  }

  public function destroy(User $user)
  {
    //
  }
}
