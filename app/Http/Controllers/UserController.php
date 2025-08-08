<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Currency;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $user,
            'address' => $user->addressName,
        ]);
    }

    public function categories(Request $request)
    {
        $user = $request->user();
        $categories = $user->categories()->get();

        return response()->json($categories);
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'emoji' => 'nullable|string|max:10',
        ]);

        $user = $request->user();

        $category = $user->categories()->create([
            'name' => $request->name,
            'emoji' => $request->emoji,
        ]);

        return response()->json($category, 201);
    }

    public function addTransaction(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:gelir,gider',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|max:10',
            'category_name' => 'required|string|max:255',
            'category_emoji' => 'required|string|max:10',
            'description' => 'nullable|string',
        ]);

        $user = $request->user();

        $transaction = $user->transactions()->create([
            'type' => $request->type,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'category_name' => $request->category_name,
            'category_emoji' => $request->category_emoji,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'transaction' => $transaction,
        ], 201);
    }

    public function transactions(Request $request)
    {
        $user = $request->user();

        $transactions = $user->transactions()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'transactions' => $transactions,
        ]);
    }

    public function getTransaction(Request $request, $id)
    {
        $user = $request->user();

        $transaction = $user->transactions()->findOrFail($id);

        return response()->json($transaction);
    }

    public function updateTransaction(Request $request, $id)
    {
        $request->validate([
            'description' => 'nullable|string',
            'type' => 'required|string|in:gelir,gider',
            'amount' => 'required|numeric|min:0.01',
            'created_at' => 'required|date',
        ]);

        $user = $request->user();
        $transaction = $user->transactions()->findOrFail($id);

        $transaction->update([
            'description' => $request->description,
            'type' => $request->type,
            'amount' => $request->amount,
            'created_at' => $request->created_at,
        ]);

        return response()->json([
            'success' => true,
            'transaction' => $transaction,
        ]);
    }


    public function getCurrencies(Request $request)
    {
        $user = $request->user();

        $userCurrencies = Currency::where('user_id', $user->id)->pluck('symbol')->toArray();
        $defaultCurrencies = Currency::whereNull('user_id')->pluck('symbol')->toArray();

        return response()->json(array_values(array_unique([...$defaultCurrencies, ...$userCurrencies])));
    }

    public function addCurrency(Request $request)
    {
        $request->validate([
            'symbol' => 'required|string|max:10',
        ]);

        $user = $request->user();
        $symbol = $request->symbol;

        $exists = Currency::where('symbol', $symbol)->where('user_id', $user->id)->exists();

        if ($exists) {
            return response()->json(['message' => 'Zaten mevcut.'], 200);
        }

        $currency = Currency::create([
            'user_id' => $user->id,
            'symbol' => $symbol,
        ]);

        return response()->json($currency, 201);
    }
}
