<?php

namespace App\Http\Resources;

use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $type = Type::find($this->type_id);
        $columnRows = $type->columnRows()->get();
        foreach ($columnRows as $columnRow) {
            $rowName = $columnRow->row()->get()->pluck('name');
            $columnName = $columnRow->column()->get()->pluck('name');
            $columnRowNames[] = "ردیف" . " " . $rowName[0] . "-" . "ستون" . " " . $columnName[0];
        }
        return [
            'name' => $this->name,
            'author' => $this->author,
            'description' => $this->description,
            'release_date' => Jalalian::fromCarbon(Carbon::parse($this->release_date))->format('Y-m-d'),
            'number_of_pages' => $this->number_of_pages,
            // 'created_at' => Jalalian::fromCarbon($this->created_at)->format('Y-d-m'),
            'type' => $this->type()->get()->pluck('name'),
            'place' =>  $columnRowNames
        ];
    }
}
