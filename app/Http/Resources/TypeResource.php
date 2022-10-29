<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $columnRows = $this->columnRows()->get();
        foreach ($columnRows as $columnRow) {
            $rowName = $columnRow->row()->get()->pluck('name');
            $columnName = $columnRow->column()->get()->pluck('name');
            $columnRowNames[] = "ردیف" . " " . $rowName[0] . "-" . "ستون" . " " . $columnName[0];
        }
        return [
            'name' => $this->name,
            'place' => $columnRowNames
        ];
    }
}
