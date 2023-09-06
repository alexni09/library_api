<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExemplarResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'borrowable' => $this->borrowable,
            'book_id' => $this->book_id,
            'book_name' => $this->book->name,
            'condition_value' => $this->condition->value,
            'condition_name' => $this->condition->name
        ];
    }
}