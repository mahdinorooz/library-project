<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Traits\HasRoles;

class BackOfficeUserResource extends JsonResource
{
    use HasRoles;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return 
        [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'national_code' => $this->national_code,
            'mobile' => $this->mobile,
            'address' => $this->address,
            'degree' => $this->degree,
            'email' => $this->email,
            'roles' => $this->getRoleNames()
        ];
    }
}
