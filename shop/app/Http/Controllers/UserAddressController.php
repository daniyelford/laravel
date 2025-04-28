<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_account_id' => 'required|exists:users_account,id',
            'address' => 'required|string',
            'code_posty' => 'required|string',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
        ]);

        $userAddress = UserAddress::create($validated);

        return response()->json($userAddress, 201);
    }

    public function show($id)
    {
        $userAddress = UserAddress::where('user_account_id', $id)->first();
        return response()->json($userAddress);
    }
}
