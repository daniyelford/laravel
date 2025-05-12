<?php

namespace App\Http\Controllers;

use App\Models\BardashtAzAccount;
use Illuminate\Http\Request;

class BardashtAzAccountController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_account_id' => 'required|exists:users_account,id',
            'user_cart_id' => 'required|exists:users_cart,id',
            'meghdar' => 'required|numeric',
            'time' => 'required|date',
            'vaziate_entghal_b_hesab_karbar' => 'required|in:pending,completed,failed',
        ]);

        $bardasht = BardashtAzAccount::create($validated);

        return response()->json($bardasht, 201);
    }

    public function show($id)
    {
        $bardasht = BardashtAzAccount::findOrFail($id);
        return response()->json($bardasht);
    }
}
