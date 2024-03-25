<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => (int) $this->id,
            'title'         => (string) $this->title,
            'description'   => (string) $this->description,
            'created_at'    => Carbon::make($this->created_at)->toDateTimeString(),
            'updated_at'    => Carbon::make($this->updated_at)->toDateTimeString(),
        ];
    }
}
