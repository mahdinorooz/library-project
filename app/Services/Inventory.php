<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Deposit;
use App\Models\BookUser;
use App\Models\Penalty;
use App\Models\Withdrawal;

class Inventory
{
    public function walletInventory($user_id)
    {
        $deposits = Deposit::where('user_id', $user_id)->where('status', 1)->sum('amount');
        $withdrawals = Withdrawal::where('user_id', $user_id)->sum('amount');
        $booksIds = BookUser::where(function ($query) {
            $query->where('status', 'is_reserved')->orWhere('status', 'is_borrowed');
        })->where(function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->get()->pluck('book_id');
        $penalties = Penalty::where('user_id', $user_id)->sum('amount');
        $price = Book::whereIn('id', $booksIds)->sum('price');
        $inventory = $deposits - $withdrawals - $price - $penalties;
        return $inventory;
    }
}
