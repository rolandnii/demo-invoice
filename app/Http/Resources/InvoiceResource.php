<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'invoice_id' => $this->id,
            'customer_id' => $this->customer_id,
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'total_amount' => $this->total_amount,
            'items' => InvoiceItemResource::collection($this->items)
        ];
    }
}
