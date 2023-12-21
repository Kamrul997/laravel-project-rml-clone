<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BankCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bank_name' => $this->bank_name,
            'short_name' => $this->short_name,
            'account_no' => $this->account_no,
            'internet_bank' => $this->internet_bank,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'branch' => [
               [ 'id' => $this->id,
                'bank_id' => $this->id,
                'branch_name' => 'N/A',
                'status' => 1,]
            ],
        ];
    }
}
