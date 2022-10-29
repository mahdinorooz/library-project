<?php

namespace App\Http\Controllers\User;

use App\Traits\PaymentTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\ViewController;
use App\Models\Deposit;
use App\Traits\SmsTrait;

class PaymentController extends Controller
{
    use PaymentTrait, SmsTrait;
    public static function sendRequest($request)
    {
        $api = env('PAY_IR_API_KEY');
        $amount = $request->amount;
        $mobile = auth()->user()->mobile;
        $factorNumber = "شماره فاکتور";
        $description = $request->description;
        $redirect = env('PAY_IR_CALLBACK_URL');
        $result = self::send($api, $amount, $redirect, $mobile, $factorNumber, $description);
        $result = json_decode($result);
        if ($result->status) {
            if ($description == 'wallet') {
                $r = Deposit::create([
                    'user_id' => auth()->user()->id,
                    'amount' => $amount,
                    'token' => $result->token,
                ]);
                if ($r) {
                    $go = "https://pay.ir/pg/$result->token";
                    return response()->json([
                        'url' => $go,
                        'token' => $result->token,
                        'status' => $result->status,
                    ]);
                } else {
                    return response()->json('مشکلی به وجود امده است', 500);
                }
            }
        } else {
            return response()->json($result->errorMessage, 422);
        }
    }
    public static function verification(Request $request)
    {
        $api = env('PAY_IR_API_KEY');
        $token = $request->token;
        $tokenValidate = Deposit::where('token', $token)->first();
        if ($tokenValidate) {
            $result = json_decode(self::verify($api, $token));
            if (isset($result->status)) {
                if ($result->status == 1) {
                    if ($result->description == 'wallet') {
                        if (Deposit::where('transaction_id', $result->transId)->exists()) {
                            return redirect()->action(
                                [ViewController::class, 'index'],
                                ['result' => ['error' => 'این تراکنش قبلا ثبت شده است']]
                            );
                        }
                        $tokenValidate->update([
                            'transaction_id' => $result->transId,
                            'status' => $result->status,
                        ]);
                        self::sendSms($result->mobile, "با سلام کاربر محترم کیف پول شما به مبلغ $result->amount ریال شارژ شد\n شماره تراکنش : $result->transId \n شماره پیگیری : $result->traceNumber");
                        return redirect()->action(
                            [ViewController::class, 'index'],
                            ['result' => $result]
                        );
                    }
                } else {
                    return redirect()->action(
                        [ViewController::class, 'index'],
                        ['result' => ['error' => 'تراکنش با خطا مواجه شد']]
                    );
                }
            } else {
                if ($request->status == 0) {
                    return redirect()->action(
                        [ViewController::class, 'index'],
                        ['result' => ['error' => 'تراکنش با خطا مواجه شد']]
                    );
                }
            }
        }
    }
}
