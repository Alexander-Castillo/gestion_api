<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryTransactionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // devuelve un arreglo asociativo con claves y valores de campos personalizados
        return [
            'id' => $this->id,
            'user_name' => $this->user_name,
            'product_name' => $this->product_name,
            'unity_price' => $this->unity_price,
            'total_price' => $this->total_price,
            'transaction_type' => $this->transaction_type,
            'created_at' => Carbon::parse($this->created_at)->format('d-m-Y'),
        ];
    }
}
