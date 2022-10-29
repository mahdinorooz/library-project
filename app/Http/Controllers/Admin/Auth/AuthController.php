<?php

namespace App\Http\Controllers\Admin\Auth;

use Carbon\Carbon;
use App\Traits\SmsTrait;
use Illuminate\Http\Request;
use App\Models\BackOfficeUser;
use App\Models\BackOfficeUserOtps;
use App\Http\Controllers\Controller;
use App\Http\Requests\BackOfficeUserRequest;

class AuthController extends Controller
{
    use SmsTrait;
    public function login(BackOfficeUserRequest $request)
    {
        $inputs = $request->all();
        $backOfficeUser = BackOfficeUser::where('mobile', $inputs['mobile'])->first();
        if (empty($backOfficeUser)) {
            return response()->json('.شما به عنوان کاربر ویژه عضو سایت نمی باشید', 403);
        }
        $otpCode = rand(111111, 999999);
        $otpInputs = [
            'user_id' => $backOfficeUser->id,
            'otp_code' => $otpCode,
            'mobile' => $inputs['mobile']
        ];
        BackOfficeUserOtps::create($otpInputs);
        // $this->sendSms($inputs['mobile'], "باسلام\nکاربر گرامی کد ورود شما به سامانه : \n $otpCode");
        return response()->json('رمز عبور یکبار مصرف برای شماره همراه شما ارسال گردید', 200);
    }

    public function loginConfirm(BackOfficeUserRequest $request)
    {
        $inputs = $request->all();
        $backOfficeUserOtp = BackOfficeUserOtps::where('mobile', $inputs['mobile'])->where('used', 0)->where('created_at', '>=', Carbon::now()->subMinute(5)->toDateTimeString())->first();
        if (empty($backOfficeUserOtp) || $backOfficeUserOtp->otp_code !== $inputs['otp']) {
            return response()->json('رمز یکبارمصرف وارد شده اشتباه می باشد', 203);
        }
        $backOfficeUserOtp->update(['used' => 1]);
        $backOfficeUser = $backOfficeUserOtp->backOfficeUser()->first();
        if (empty($backOfficeUser->mobile_verified_at)) {
            $backOfficeUser->update(['mobile_verified_at' => Carbon::now()]);
        }
        $token = $backOfficeUser->createToken('backOfficeUser-token')->plainTextToken;
        return response()->json([
            'message' => 'you logged in successfully',
            'token' => $token,
            'user' => $backOfficeUser
        ], 200);
    }

    public function logout()
    {
        $backOfficeUser = auth()->user();
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'you logged out successfully',
            'user' => $backOfficeUser
        ], 200);
    }
}
