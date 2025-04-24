<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $wallet = $request->user()->wallet;
        $wallet->balance += $request->amount;
        $wallet->save();

        return response()->json([
            'message' => 'مبلغ با موفقیت واریز شد.',
            'balance' => $wallet->balance,
        ]);
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $wallet = $request->user()->wallet;

        if ($wallet->balance < $request->amount) {
            return response()->json([
                'message' => 'موجودی کافی نیست.',
            ], 400);
        }

        $wallet->balance -= $request->amount;
        $wallet->save();

        return response()->json([
            'message' => 'برداشت با موفقیت انجام شد.',
            'balance' => $wallet->balance,
        ]);
    }

    public function show()
    {
        return response()->json([
            'balance' => auth()->user()->wallet->balance,
        ]);
    }
    public function getBalance()
    {
        return response()->json([
            'balance' => auth()->user()->wallet->balance,
        ]);
    }
}
