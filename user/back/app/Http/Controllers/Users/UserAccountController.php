<?php

namespace App\Http\Controllers\Users;

use App\Models\Users\UserAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class UserAccountController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_mobile_id' => 'required|exists:users_mobile,id',
            'mojodi_account' => 'required|numeric',
        ]);

        $userAccount = UserAccount::create($validated);

        return Response::json($userAccount, 201);
    }

    public function show($id)
    {
        $userAccount = UserAccount::findOrFail($id);
        return Response::json($userAccount);
    }
}
