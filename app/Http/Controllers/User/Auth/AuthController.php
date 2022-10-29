<?php

namespace App\Http\Controllers\User\Auth;

use Carbon\Carbon;
use App\Models\Otp;
use App\Models\User;
use App\Traits\SmsTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRegisterRequest;

class AuthController extends Controller
{
    use SmsTrait;
    public function loginRegister(LoginRegisterRequest $request)
    {
        $inputs = $request->all();
        $user = User::where('mobile', $inputs['mobile'])->first();
        if (empty($user)) {
            $newUser['mobile'] = $inputs['mobile'];
            $newUser['password'] = '98355154';
            $newUser['activation'] = 1;
            $user = User::create($newUser);
        }
        $otpCode = rand(111111, 999999);
        $otpInputs = [
            'user_id' => $user->id,
            'otp_code' => $otpCode,
            'mobile' => $inputs['mobile']
        ];
        Otp::create($otpInputs);
        $this->sendSms($inputs['mobile'], "باسلام\nکاربر گرامی کد ورود شما به سامانه : \n $otpCode");
        return response()->json('رمز عبور یکبار مصرف برای شماره همراه شما ارسال گردید', 200);
    }

    public function loginConfirm(LoginRegisterRequest $request)
    {
        $inputs = $request->all();
        $otp = Otp::where('mobile', $inputs['mobile'])->where('used', 0)->where('created_at', '>=', Carbon::now()->subMinute(5)->toDateTimeString())->first();
        if (empty($otp) || $otp->otp_code !== $inputs['otp']) {
            return response()->json('رمز یکبارمصرف وارد شده اشتباه می باشد', 203);
        }
        $otp->update(['used' => 1]);
        $user = $otp->user()->first();
        if (empty($user->mobile_verified_at)) {
            $user->update(['mobile_verified_at' => Carbon::now()]);
        }
        $token = $user->createToken('user-token')->plainTextToken;
        return response()->json([
            'message' => 'you logged in successfully',
            'token' => $token,
            'user' => $user
        ], 200);
    }

    public function logout()
    {
        $user = auth()->user();
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'you logged out successfully',
            'user' => $user
        ], 200);
    }
}
