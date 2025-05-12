<?php

namespace App\Http\Controllers\Users;

use App\Models\Users\Variz;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class VarizController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_account_id' => 'required|exists:users_account,id',
            'meghdar' => 'required|numeric',
            'factor_pardakht' => 'required|string',
            'time' => 'required|date',
        ]);

        $variz = Variz::create($validated);

        return Response::json($variz, 201);
    }

    public function show($id)
    {
        $variz = Variz::findOrFail($id);
        return Response::json($variz);
    }
}
