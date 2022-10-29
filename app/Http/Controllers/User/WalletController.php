<?php

namespace App\Http\Controllers\User;

use App\Models\Book;
use App\Models\Deposit;
use App\Models\Penalty;
use App\Models\BookUser;
use App\Models\Withdrawal;
use App\Services\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WalletController extends Controller
{
    public function walletInventory()
    {
        $deposits = Deposit::where('user_id', auth()->user()->id)->where('status', 1)->sum('amount');
        $withdrawals = Withdrawal::where('user_id', auth()->user()->id)->sum('amount');
        $booksIds = BookUser::where(function ($query) {
            $query->where('status', 'is_reserved')->orWhere('status', 'is_borrowed');
        })->where(function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->get()->pluck('book_id');
        $penalties = Penalty::where('user_id', auth()->user()->id)->sum('amount');
        $price = Book::whereIn('id', $booksIds)->sum('price');
        $inventory = $deposits - $withdrawals - $price - $penalties;
        return response()->json(['موجودی' => $inventory . "ریال"]);
    }
    public function withdrawal(Request $request)
    {
        $i = new Inventory();
        $inventory = $i->walletInventory(auth()->user()->id);
        if ($request->amount <= $inventory) {
            Withdrawal::create([
                'user_id' => auth()->user()->id,
                'amount' => $request->amount,
            ]);
            return response()->json('درخواست برداشت شما با موفقیت ثبت شد', 200);
        } else {
            return response()->json('مبلغ درخواستی از موجودی کیف پول شما بیشتر است', 403);
        }
    }
}
