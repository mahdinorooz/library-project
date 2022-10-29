<?php

namespace App\Http\Resources;

use NumberFormatter;
use Illuminate\Http\Resources\Json\JsonResource;

class RowResource extends JsonResource
{
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
            'name' => $this->name,
            'columns' => $this->columns()->get()->pluck('name')
        ];
    }
}
