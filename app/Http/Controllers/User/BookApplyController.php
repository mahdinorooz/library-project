<?php

namespace App\Http\Controllers\User;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\BookUser;
use App\Services\Inventory;
use Carbon\Carbon;

class BookApplyController extends Controller
{
    public function bookApply(Book $book)
    {
        $inventory = new Inventory();
        $walletInventory = $inventory->walletInventory(auth()->user()->id);
        if ($book->is_locked == false) {
            if ($book->price <= $walletInventory) {
                DB::beginTransaction();
                $book->update([
                    'is_locked' => '1'
                ]);
                BookUser::create([
                    'book_id' => $book->id,
                    'user_id' => auth()->user()->id,
                    'status' => '1',
                    'reserve_date' => Carbon::now(),
                ]);
                DB::commit();
                return response()->json('کتاب مورد نظر شما با موفقیت رزرو شد لطفا به کتابخانه مراجعه فرمایید', 200);
            } else
                return response()->json('.قیمت کتاب مورد نظر از موجودی کیف پول شما بیشتر است ابتدا موجودی خود را افزایش دهید و سپس نسبت به رزرو اقدام فرمایید', 403);
        } else {
            return response()->json('کتاب موردنظر قبلا رزرو یا به امانت داده شده است', 403);
        }
    }
}
