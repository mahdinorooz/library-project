<?php

namespace App\Http\Controllers\User;

use App\Traits\PaymentTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DepositRequest;
use App\Models\Deposit;
use PHPUnit\Framework\MockObject\Stub\ReturnSelf;

use function PHPUnit\Framework\returnSelf;
use function PHPUnit\Framework\returnValue;

class DepositController extends Controller
{
    use PaymentTrait;
    public function deposit(DepositRequest $request)
    {
       return PaymentController::sendRequest($request);
    }
}
