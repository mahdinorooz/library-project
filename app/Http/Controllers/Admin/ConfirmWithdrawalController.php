<?php

namespace App\Http\Controllers\Admin;

use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotConfirmedWithdrawals;
use App\Models\User;
use Carbon\Carbon;

class ConfirmWithdrawalController extends Controller
{
    public function notConfirmedWithdrawals()
    {
        $notConfirmedWithdrawals = Withdrawal::where('status',0)->get();
        return NotConfirmedWithdrawals::collection($notConfirmedWithdrawals);
    }
    public function confirmWithdrawal(Withdrawal $withdrawal)
    {
        $withdrawal->update([
            'transaction_id' => rand(11111111,99999999),
            'pay_date' => Carbon::now(),
            'status' => 1,
        ]);
        return response()->json('تراکنش مورد نظر ثبت گردید');
    }
}
