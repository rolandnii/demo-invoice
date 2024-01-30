<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "description" => $this->item_description,
            'unit_price' => $this->unit_price,
            'subtotal' => $this->subtotal,
            'quantity' => $this->quantity,
        ];
    }
}
