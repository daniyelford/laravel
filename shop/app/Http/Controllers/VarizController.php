<?php

namespace App\Http\Controllers;

use App\Models\Variz;
use Illuminate\Http\Request;

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

        return response()->json($variz, 201);
    }

    public function show($id)
    {
        $variz = Variz::findOrFail($id);
        return response()->json($variz);
    }
}
