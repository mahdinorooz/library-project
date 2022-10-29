<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class NotConfirmedWithdrawals extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = User::where('id',$this->user_id)->first();
        if ($this->status == 0) {
            $status = 'پرداخت نشده';
        } else
            $status = 'پرداخت شده';
        return [
            'user_id' => $user->first_name . " " . $user->last_name,
            'amount' => $this->amount,
            'transaction_id' => $this->transaction_id,
            'pay_date' => $this->pay_date,
            'status' => $status,
        ];
    }
}
