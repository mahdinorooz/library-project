<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\BackOfficeUser;
use App\Models\Book;
use Illuminate\Http\Request;

class BookListController extends Controller
{
    public function reservedBooks()
    {
        // $user = BackOfficeUser::find(1);
        // dd($user->can('reserved-book'));
        $books = Book::where('status', '1')->get();
        return BookResource::collection($books->load('users'));
    }
}
