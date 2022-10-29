<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\BackOfficeUser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Http\Resources\BackOfficeUserResource;

class UserRoleController extends Controller
{
    use HasRoles;
    public function backOfficeUserList()
    {
        $backOfficeUsers = BackOfficeUser::where('national_code', '!=', 2981196523)->orWhereNull('national_code')->get();
        return BackOfficeUserResource::collection($backOfficeUsers);
    }

    public function assignRoleToUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'roles' => 'required|exists:roles,id',
            'first_name' => 'required|max:120|min:1|regex:/^[ا-یa-zA-Zء-ي ]+$/u',
            'last_name' => 'required|max:120|min:1|regex:/^[ا-یa-zA-Zء-ي ]+$/u',
            'mobile' => ['required', 'digits:11', 'unique:users'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'unique:users', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(), 'confirmed'],
            'activation' => 'required|numeric|in:0,1',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages());
        }
        DB::beginTransaction();
        $backOfficeUser = BackOfficeUser::create($request->all());
        // dd($backOfficeUser);
        $backOfficeUser->assignRole($request->roles);
        DB::commit();
        return response()->json(['date' => BackOfficeUserResource::make($backOfficeUser), 'message' => 'success'], 200);
    }

    public function editUserRole(BackOfficeUser $backOfficeUser, Request $request)
    {
        if ($backOfficeUser->id == 1) {
            return response()->json('کاربر مورد نظر مجاز به انجام این عملیات نمیباشد', 403);
        }
        $validator = Validator::make($request->all(), [
            'roles' => 'required|exists:roles,id'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages());
        }
        $backOfficeUser->syncRoles($request->all());
        return response()->json('نقش های کاربر مورد نظر با موفقیت تغییر یافت', 200);
    }

    public function destroy(BackOfficeUser $backOfficeUser)
    {
        if ($backOfficeUser->id == 1) {
            return response()->json('کاربر مورد نظر مجاز به انجام این عملیات نمیباشد', 403);
        }
        $roles = $backOfficeUser->roles->pluck('id')->toArray();
        // dd($roles);
        foreach ($roles as $role) {
            $backOfficeUser->removeRole($role);
        }
        $backOfficeUser->delete();
        return response()->json('کاربر مورد نظر با موفقیت حذف شد', 200);
    }
}
