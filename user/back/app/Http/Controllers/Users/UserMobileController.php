<?php

namespace App\Http\Controllers\Users;

use App\Models\Users\UserMobile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;


class UserMobileController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'mobile' => 'required|string|max:255|unique:users_mobile',
        ]);

        $userMobile = UserMobile::create($validated);

        return Response::json($userMobile, 201);
    }

    public function show($id)
    {
        $userMobile = UserMobile::where('user_id', $id)->first();
        return Response::json($userMobile);
    }
}
