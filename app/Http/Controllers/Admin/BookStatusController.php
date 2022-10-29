<?php

namespace App\Http\Controllers\Admin;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BookUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class BookStatusController extends Controller
{
    public function changeStatus(Book $book, User $user, Request $request)
    {
        // dd($book);
        $validator = Validator::make($request->all(), [
            // 'user_id' => 'required|min:1|max:100000000|regex:/^[0-9]+$/u|exists:users,id',
            'return_date' => 'required|after:now|date',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }
        $bookUSer = BookUser::where('borrow_date', null)->where('book_id', $book->id)->where('user_id', $user->id)->first();
        $bookUSer->update([
            'status' => '2',
            'borrow_date' => Carbon::now()
        ]);
        $book->update([
            'return_date' => $request->return_date
        ]);
        return response()->json('کتاب مورد نظر به امانت داده شد');
    }

    public function bookDelivery(Book $book, User $user, Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'user_id' => 'required|min:1|max:100000000|regex:/^[0-9]+$/u|exists:users,id',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json($validator->messages(), 422);
        // }
        $bookUSer = BookUser::where('return_date', null)->where('book_id', $book->id)->where('user_id', $user->id)->first();
        $bookUSer->update([
            'status' => '3',
            'return_date' => Carbon::now()
        ]);
        $book->update([
            'is_locked' => false
        ]);
        return response()->json('کتاب موردنظر تحویل گرفته شد');
    }
}
