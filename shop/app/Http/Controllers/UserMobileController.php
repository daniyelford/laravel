<?php

namespace App\Http\Controllers;

use App\Models\UserMobile;
use Illuminate\Http\Request;

class UserMobileController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'mobile' => 'required|string|max:255|unique:users_mobile',
        ]);

        $userMobile = UserMobile::create($validated);

        return response()->json($userMobile, 201);
    }

    public function show($id)
    {
        $userMobile = UserMobile::where('user_id', $id)->first();
        return response()->json($userMobile);
    }
}
