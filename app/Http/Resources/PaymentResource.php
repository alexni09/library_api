<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'exemplar_id' => $this->exemplar_id,
            'due_value' => $this->due_value,
            'due_from' => $this->due_from,
            'due_at' => $this->due_at,
            'paid_at' => $this->paid_at
        ];
    }
}