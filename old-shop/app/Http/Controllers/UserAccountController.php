<?php

namespace App\Http\Controllers;

use App\Models\UserAccount;
use Illuminate\Http\Request;

class UserAccountController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_mobile_id' => 'required|exists:users_mobile,id',
            'mojodi_account' => 'required|numeric',
        ]);

        $userAccount = UserAccount::create($validated);

        return response()->json($userAccount, 201);
    }

    public function show($id)
    {
        $userAccount = UserAccount::findOrFail($id);
        return response()->json($userAccount);
    }
}
