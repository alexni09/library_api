<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'rating_value' => $this->rating->value,
            'rating_name' => $this->rating->name,
            'category_id' => $this->category_id,
            'category_name' => $this->category->name
        ];
    }
}