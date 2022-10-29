<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserEditProfileRequest;
use App\Http\Resources\UserRecource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserEditProfileController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        return UserRecource::make($user);
    }

    public function edit(UserEditProfileRequest $request)
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        // dd($user);
        $user->update($request->all());
    }
}
