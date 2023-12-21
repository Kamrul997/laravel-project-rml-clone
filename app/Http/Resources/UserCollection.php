<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    protected $units;

    public function __construct($resource, $units)
    {
        parent::__construct($resource);
        $this->units = $units;
    }
    public function toArray(Request $request)
    {

        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => 'test address',
            'user_type' => $this->roles->pluck('name')->first(),
            'zone_id' => $this->zone_id,
            'area_id' => $this->area_id,
            'unit_id' => $this->unit_id,
            'image' => $this->image,
            'permissions' => $this->getAllPermissions()->pluck('name'),
            'unit_array' => $this->units,
        ];
    }
}
