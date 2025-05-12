<?php

namespace App\Http\Controllers\Users;

use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return Response::json($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'code_mely' => 'required|string|max:255|unique:users',
        ]);

        $user = User::create($validated);

        return Response::json($user, 201);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return Response::json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'family' => 'string|max:255',
            'code_mely' => 'string|max:255|unique:users,code_mely,' . $id,
        ]);

        $user->update($validated);

        return Response::json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return Response::json(null, 204);
    }
}
